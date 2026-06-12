<?php

namespace App\Http\Controllers;

use App\espacio;
use App\Http\Requests\Espacios\ResolveSolicitudEspacioRequest;
use App\Http\Requests\Espacios\StoreSolicitudEspacioRequest;
use App\Services\Espacios\SolicitudEspacioService;
use App\SolicitudEspacio;
use Illuminate\Support\Facades\Auth;

class SolicitudEspacioController extends Controller
{
    private const STATUS_ICONS = [
        SolicitudEspacio::PENDING => 'far fa-clock fa-2x',
        SolicitudEspacio::APPROVED => 'far fa-check fa-2x',
        SolicitudEspacio::REJECTED => 'far fa-times fa-2x',
    ];

    private SolicitudEspacioService $solicitudes;

    public function __construct(SolicitudEspacioService $solicitudes)
    {
        $this->middleware("auth");
        $this->solicitudes = $solicitudes;

    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $solicitudes = Auth::user()
            ->solicitudesEspacio()
            ->with('espacio')
            ->orderByDesc('id')
            ->get();

        return view("espacio.listado",[
            "solicitudes" => $solicitudes,
            "icon" => self::STATUS_ICONS,
        ]);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function listado(){
        $solicitudes = SolicitudEspacio::with(['user', 'espacio'])
            ->pending()
            ->orderByDesc('id')
            ->get();

        return view("espacio.autorizar",[
            "solicitudes" => $solicitudes,
            "icon" => self::STATUS_ICONS,
        ]);
    }

    public function historial(){
        $solicitudes = SolicitudEspacio::with(['user', 'espacio'])
            ->resolved()
            ->orderByDesc('id')
            ->get();

        return view("espacio.historial",[
            "solicitudes" => $solicitudes,
            "icon" => self::STATUS_ICONS,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view("espacio.nuevaSolicitud",[
            "espacios" => espacio::all()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreSolicitudEspacioRequest $request)
    {
        $solicitud = $this->solicitudes->create(Auth::user(), $request->validated());

        return view("espacio.nuevaSolicitud",[
            "espacios" => espacio::all(),
            "result" => $solicitud != null
        ]);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $solicitud = SolicitudEspacio::with(['user', 'espacio'])->findOrFail($id);
        abort_unless($solicitud->user_id === Auth::id(), 403);

        $estado = [
          1 => "Autorizado",
          2 => "Rechazado"
        ];

        return view("espacio.show",[
            "item" => $solicitud,
            "estado" => $estado
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $solicitud = SolicitudEspacio::with(['user', 'espacio'])->findOrFail($id);

        return view("espacio.definir",[
            "item" => $solicitud
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ResolveSolicitudEspacioRequest $request)
    {
        $data = $request->validated();
        $solicitud = SolicitudEspacio::with('user')->findOrFail($data["id"]);
        $this->solicitudes->resolve($solicitud, (int) $data["estado"], $data["respuesta"]);

        return redirect("/espacios/solicitudes/autorizar");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $solicitud = SolicitudEspacio::findOrFail($id);
        abort_unless($solicitud->user_id === Auth::id(), 403);
        abort_if((int) $solicitud->autorizado === SolicitudEspacio::APPROVED, 403);

        $solicitud->delete();

        return redirect("/espacios/solicitudes/list");
    }
}
