<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSignalementRequest;
use App\Http\Resources\SignalementResource;
use App\Models\Signalement;
use Illuminate\Http\Request;
use App\Http\Requests\UpdateSignalementRequest;
use App\Http\Requests\UpdateSignalementStatusRequest;
use Illuminate\Support\Facades\Gate;

class SignalementController extends Controller
{
    public function store(StoreSignalementRequest $request)
    {
        $data = $request->validated();

        $data['user_id'] = auth()->id();
 $data['status'] = 'nouveau';
      if ($request->hasFile('photo')) {
        $data['photo'] = $request->file('photo')->store('signalements', 'public');
    }
        $signalement = Signalement::create($data);

        return new SignalementResource($signalement);
    }

    public function index()
{
    $signalements = Signalement::where('user_id', auth()->id())
        ->latest()
        ->get();

    return SignalementResource::collection($signalements);
}

public function show(Signalement $signalement)
{
    if ($signalement->user_id !== auth()->id()) {
        abort(403, 'Vous n\'êtes pas autorisé à consulter ce signalement.');
    }

    return new SignalementResource($signalement);
}

public function update(
    UpdateSignalementRequest $request,
    Signalement $signalement
) {
    $data = $request->validated();

    if ($request->hasFile('photo')) {
        $data['photo'] = $request->file('photo')
            ->store('signalements', 'public');
    }

    $signalement->update($data);

    return new SignalementResource($signalement);
}

 public function updateStatus(
    UpdateSignalementStatusRequest $request,
    Signalement $signalement
) {
    Gate::authorize('updateStatus', $signalement);

    $signalement->update([
        'status' => $request->status,
    ]);

    return new SignalementResource($signalement);
}
}