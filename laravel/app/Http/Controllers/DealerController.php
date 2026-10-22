<?php
namespace App\Http\Controllers;
use App\Http\Requests\StorePriceRequest;
use App\Mail\DealerRequestMail;
use App\Models\Lead;
use App\Models\LeadRecipient;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;
class DealerController extends Controller
{
    public function create(): View
    {
        return view('dealer.create');
    }
    public function store(StorePriceRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $name = trim((string) ($data['organization_name'] ?? ''));
        if ($name === '') {
            $name = 'Без названия';
        }
        $lead = Lead::query()->create([
            'type' => 'dealer_request',
            'name' => $name,
            'phone' => $data['phone'],
            'email' => $data['email'] ?? '',
            'inn' => $data['inn'] ?? null,
            'city' => null,
            'comment' => $data['comment'] ?? null,
            'contact_preference' => $data['work_format'] ?? null,
            'status' => 'new',
        ]);
        $recipients = LeadRecipient::query()
            ->where('is_active', true)
            ->orderBy('sort')
            ->pluck('email')
            ->filter()
            ->unique()
            ->values()
            ->all();
        if ($recipients === []) {
            $recipients = [config('mail.from.address')];
        }
        dispatch(function () use ($recipients, $lead) {
            Mail::to($recipients)->send(new DealerRequestMail($lead));
        })->afterResponse();
        return redirect()->route('dealer.thanks');
    }
    public function thanks(): View
    {
        return view('dealer.thanks');
    }
}
