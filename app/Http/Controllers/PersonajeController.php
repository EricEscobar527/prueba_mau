<?php

namespace App\Http\Controllers;

use App\Http\Requests\PersonajeRequest;
use App\Models\Personaje;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class PersonajeController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Personajes/Index', [
            'personajes' => Personaje::latest('id')->paginate(10),
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Personajes/Crear');
    }

    public function store(PersonajeRequest $request): RedirectResponse
    {
        Personaje::create($request->validated());

        return redirect()->route('personajes.index')
            ->with('success', 'Personaje creado correctamente.');
    }

    public function edit(Personaje $personaje): Response
    {
        return Inertia::render('Personajes/Editar', [
            'personaje' => $personaje,
        ]);
    }

    public function update(PersonajeRequest $request, Personaje $personaje): RedirectResponse
    {
        $personaje->update($request->validated());

        return redirect()->route('personajes.index')
            ->with('success', 'Personaje actualizado correctamente.');
    }

    public function destroy(Personaje $personaje): RedirectResponse
    {
        $personaje->delete();

        return back()->with('success', 'Personaje eliminado correctamente.');
    }
}
