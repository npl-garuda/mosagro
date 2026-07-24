<?php
namespace App\Http\Controllers;
use App\Http\Requests\StorePriceRequest;
use App\Mail\PriceRequestMail;
use App\Models\Lead;
use App\Models\LeadRecipient;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;
class PriceController extends Controller
{
    public function create(): View
    {
        return view('price.create');
    }
    public function store(StorePriceRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $lead = Lead::query()->create([
            'type' => 'price_request',
            'name' => $data['name'],
            'phone' => $data['phone'],
            'email' => $data['email'],
            'inn' => $data['inn'] ?? null,
            'city' => $data['city'] ?? null,
            'comment' => $data['comment'] ?? null,
            'contact_preference' => $data['contact_preference'] ?? null,
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
        Mail::to($recipients)->send(new PriceRequestMail($lead));
        return redirect()->route('price.thanks');
    }
    public function thanks(): View
    {
        return view('price.thanks');
    }
}
