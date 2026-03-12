<?php
namespace App\Http\Controllers\Admin;

use App\Helpers\MailHelper;
use App\Http\Controllers\Controller;
use App\Mail\CampaignMail;
use App\Models\Campaign;
use App\Models\CampaignLog;
use App\Models\Subscriber;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class NewsletterController extends Controller
{
    // ── Subscribers ───────────────────────────────────────────

    public function subscribers(Request $request)
    {
        $subscribers = Subscriber::orderByDesc('created_at')->paginate(20);
        $stats = [
            'total' => Subscriber::count(),
            'active' => Subscriber::active()->count(),
            'unsub' => Subscriber::where('status', 'unsubscribed')->count(),
        ];
        return view('admin.newsletter.subscribers', compact('subscribers', 'stats'));
    }

    public function destroySubscriber(Subscriber $subscriber)
    {
        $subscriber->delete();
        return back()->with('success', 'Suscriptor eliminado.');
    }

    // ── Campaigns ─────────────────────────────────────────────

    public function campaigns()
    {
        $campaigns = Campaign::orderByDesc('created_at')->paginate(15);
        $mailReady = MailHelper::isConfigured();
        return view('admin.newsletter.campaigns', compact('campaigns', 'mailReady'));
    }

    public function createCampaign()
    {
        $campaign = new Campaign();
        $subscriberCount = Subscriber::active()->count();
        return view('admin.newsletter.campaign-form', compact('campaign', 'subscriberCount'));
    }

    public function storeCampaign(Request $request)
    {
        $data = $request->validate([
            'subject' => 'required|string|max:255',
            'preview_text' => 'nullable|string|max:300',
            'body' => 'required|string',
        ]);
        $data['status'] = 'draft';
        Campaign::create($data);
        return redirect()->route('admin.newsletter.campaigns')->with('success', 'Campaña creada como borrador.');
    }

    public function editCampaign(Campaign $campaign)
    {
        if ($campaign->status === 'sent') {
            return redirect()->route('admin.newsletter.campaign.show', $campaign)->with('info', 'No se puede editar una campaña ya enviada.');
        }
        $subscriberCount = Subscriber::active()->count();
        return view('admin.newsletter.campaign-form', compact('campaign', 'subscriberCount'));
    }

    public function updateCampaign(Request $request, Campaign $campaign)
    {
        if ($campaign->status === 'sent') {
            return back()->with('error', 'No se puede editar una campaña ya enviada.');
        }
        $data = $request->validate([
            'subject' => 'required|string|max:255',
            'preview_text' => 'nullable|string|max:300',
            'body' => 'required|string',
        ]);
        $campaign->update($data);
        return redirect()->route('admin.newsletter.campaigns')->with('success', 'Campaña actualizada.');
    }

    public function showCampaign(Campaign $campaign)
    {
        $logs = $campaign->logs()->with('subscriber')->orderByDesc('sent_at')->paginate(20);
        return view('admin.newsletter.campaign-show', compact('campaign', 'logs'));
    }

    public function destroyCampaign(Campaign $campaign)
    {
        $campaign->delete();
        return redirect()->route('admin.newsletter.campaigns')->with('success', 'Campaña eliminada.');
    }

    // ── Send Test ─────────────────────────────────────────────

    public function sendTest(Request $request, Campaign $campaign)
    {
        if (!MailHelper::isConfigured()) {
            return back()->with('error', 'SMTP no configurado. Configura las variables MAIL_* en .env antes de enviar.');
        }

        $request->validate(['test_email' => 'required|email']);

        $fakeSub = new Subscriber(['email' => $request->test_email, 'token' => 'test-preview']);

        try {
            Mail::to($request->test_email)->send(new CampaignMail($campaign, $fakeSub));
            return back()->with('success', 'Email de prueba enviado a ' . $request->test_email);
        }
        catch (\Throwable $e) {
            return back()->with('error', 'Error al enviar: ' . $e->getMessage());
        }
    }

    // ── Send Campaign ─────────────────────────────────────────

    public function sendCampaign(Campaign $campaign)
    {
        if (!MailHelper::isConfigured()) {
            return back()->with('error', 'SMTP no configurado. Configura las variables MAIL_* en .env.');
        }

        if ($campaign->status === 'sent') {
            return back()->with('error', 'Esta campaña ya fue enviada.');
        }

        $subscribers = Subscriber::active()->get();
        if ($subscribers->isEmpty()) {
            return back()->with('error', 'No hay suscriptores activos.');
        }

        $campaign->update([
            'status' => 'sending',
            'total_recipients' => $subscribers->count(),
            'sent_count' => 0,
            'failed_count' => 0,
        ]);

        $sent = 0;
        $failed = 0;

        foreach ($subscribers as $sub) {
            try {
                Mail::to($sub->email)->send(new CampaignMail($campaign, $sub));
                CampaignLog::create([
                    'campaign_id' => $campaign->id,
                    'subscriber_id' => $sub->id,
                    'status' => 'sent',
                    'sent_at' => now(),
                ]);
                $sent++;
            }
            catch (\Throwable $e) {
                CampaignLog::create([
                    'campaign_id' => $campaign->id,
                    'subscriber_id' => $sub->id,
                    'status' => 'failed',
                    'error' => $e->getMessage(),
                    'sent_at' => now(),
                ]);
                $failed++;
            }

            // Throttle: 100ms pause between emails (shared-hosting friendly)
            usleep(100_000);
        }

        $campaign->update([
            'status' => $failed === $subscribers->count() ? 'failed' : 'sent',
            'sent_count' => $sent,
            'failed_count' => $failed,
            'sent_at' => now(),
        ]);

        return redirect()->route('admin.newsletter.campaign.show', $campaign)
            ->with('success', "Campaña enviada. ✅ {$sent} enviados, ❌ {$failed} fallidos.");
    }

    // ── Mail Status ───────────────────────────────────────────

    public function mailStatus()
    {
        return view('admin.newsletter.mail-status', [
            'isConfigured' => MailHelper::isConfigured(),
            'statusMessage' => MailHelper::statusMessage(),
            'vars' => MailHelper::requiredVars(),
        ]);
    }
}
