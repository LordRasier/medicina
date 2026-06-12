<?php

namespace App\Http\Controllers;

use App\request as sol;
use App\Http\Requests\Dispensas\ResolveDispensaAuthorizationRequest;
use App\Http\Requests\Dispensas\StoreDispensaRequest;
use App\Http\Requests\Dispensas\StorePeriodExtraDaysRequest;
use App\licence;
use App\periodo;
use App\Services\Dispensas\DispensaAuthorizationService;
use App\Services\Dispensas\DispensaRequestService;
use App\Services\Periodos\PeriodExtraDaysService;
use App\Services\Periodos\PeriodCalendarService;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PeriodoController extends Controller
{
    private PeriodCalendarService $periodCalendar;
    private DispensaAuthorizationService $dispensaAuthorizations;
    private DispensaRequestService $dispensaRequests;
    private PeriodExtraDaysService $periodExtraDays;

    public function __construct(
        PeriodCalendarService $periodCalendar,
        DispensaAuthorizationService $dispensaAuthorizations,
        DispensaRequestService $dispensaRequests,
        PeriodExtraDaysService $periodExtraDays
    )
    {
        $this->middleware("auth");
        $this->periodCalendar = $periodCalendar;
        $this->dispensaAuthorizations = $dispensaAuthorizations;
        $this->dispensaRequests = $dispensaRequests;
        $this->periodExtraDays = $periodExtraDays;

    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function prev()
    {
        return $this->previousPeriod();
    }

    public function next()
    {
        return $this->nextPeriod();
    }

    public function index()
    {
        return $this->currentPeriod();
    }

    /**
     * Legacy route wrapper for the previous annual period.
     */
    public function previousPeriod()
    {
        return $this->renderOwnPeriod(-1, true, false);
    }

    /**
     * Legacy route wrapper for the next annual period.
     */
    public function nextPeriod()
    {
        return $this->renderOwnPeriod(1, false, true);
    }

    /**
     * Shows the authenticated user's current annual period.
     */
    public function currentPeriod()
    {
        return $this->renderOwnPeriod(0, true, true);
    }

    /**
     * Calculates available days using the period domain service.
     */
    public function availableDays($id)
    {
        return $this->periodCalendar->calculateAvailableDays(User::findOrFail($id));
    }

    private function renderOwnPeriod(int $yearOffset, bool $canGoNext, bool $canGoPrevious)
    {
        $user = User::findOrFail(Auth::id());
        $period = $this->periodCalendar->findOrCreateForUser($user, $yearOffset);
        $calendarData = $this->periodCalendar->buildCalendarData($user, $period);

        return view('periodo.show', array_merge($calendarData, [
            'nextno' => $canGoNext,
            'prevno' => $canGoPrevious,
        ]));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $user = User::findOrFail(Auth::id());
        $period = $this->periodCalendar->findOrCreateForUser($user);
        $calendarData = $this->periodCalendar->buildCalendarData($user, $period, false);

        return view('periodo.create', array_merge($calendarData, [
            'licencias' => licence::where('habilitado', 1)->get(),
        ]));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(StoreDispensaRequest $request)
    {
        $data = $request->validated();
        $period = periodo::findOrFail($data["periodo"]);

        $this->dispensaRequests->create(Auth::user(), $period, $data, $request->file("file"));

        return redirect("/misDispensas/solicitudes");
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

    }

    public function misSolicitudes(){
        return view("periodo.misSolicitudes",[
            "solicitudes" => sol::where("user_id", Auth::id())->orderByDesc('id')->get(),
            "icono" => $this->authorizationIcons()
        ]);
    }

    public function detail($id){
        $sol = sol::with(['days', 'licence'])->findOrFail($id);
        abort_unless((int) $sol->user_id === (int) Auth::id(), 403);

        return view("periodo.detail",[
            "periodo" => $sol
        ]);
    }

    public function auto2(){
        return view("periodo.lista2",[
            "solicitudes" => sol::waitingSecondAuthorization()->orderByDesc('id')->get(),
            "icono" => $this->authorizationIcons()
        ]);
    }

    public function auto3(){
        return view("periodo.lista3",[
            "solicitudes" => sol::waitingThirdAuthorization()->orderByDesc('id')->get(),
            "icono" => $this->authorizationIcons()
        ]);
    }

    public function detail2($id){
        $sol = sol::with(['user', 'days', 'licence'])->findOrFail($id);

        return view("periodo.definir2",[
            "solicitud" => $sol
        ]);
    }

    public function detail3($id){
        $sol = sol::with(['user', 'days', 'licence'])->findOrFail($id);

        return view("periodo.definir3",[
            "solicitud" => $sol
        ]);
    }

    public function checkfile($id){
        $sol = sol::findOrFail($id);

        abort_unless($this->canViewDispensaFile($sol), 403);
        abort_if($sol->file == null || ! Storage::exists($sol->file), 404);

        return response()->file(Storage::path($sol->file));
    }

    private function canViewDispensaFile(sol $sol){
        $user = Auth::user();

        if ($user == null) {
            return false;
        }

        if ((int) $sol->user_id === (int) $user->id) {
            return true;
        }

        return $user->canAccessAnyMenu([
            '/autorizaciones/2',
            '/autorizaciones/3',
            '/dispensas/historial',
        ]);
    }

    public function definir2(ResolveDispensaAuthorizationRequest $request){
        $data = $request->validated();
        $sol = sol::findOrFail($data["id"]);
        $this->dispensaAuthorizations->resolveSecondLevel($sol, (int) $data["estado"], $data["respuesta"]);
        $resp = ((int) $data["estado"] === sol::APPROVED);

        return view("periodo.lista2",[
            "solicitudes" => sol::waitingSecondAuthorization()->orderByDesc('id')->get(),
            "icono" => $this->authorizationIcons(),
            "resp" => $resp
        ]);

    }

    public function definir3(ResolveDispensaAuthorizationRequest $request){
        $data = $request->validated();
        $sol = sol::with('user')->findOrFail($data["id"]);
        $this->dispensaAuthorizations->resolveThirdLevel($sol, (int) $data["estado"], $data["respuesta"]);
        $resp = ((int) $data["estado"] === sol::APPROVED);

        return view("periodo.lista3",[
            "solicitudes" => sol::waitingThirdAuthorization()->orderByDesc('id')->get(),
            "icono" => $this->authorizationIcons(),
            "resp" => $resp
        ]);

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

    }

    public function disponibles($id)
    {
        return $this->availableDays($id);
    }

    public function toeditperiod(){
        return view("periodo.extrauser",[
            "users" => User::all()
        ]);
    }

    public function editperiod($id)
    {
        $user = User::findOrFail($id);
        $user->periodos = $this->periodCalendar->findOrCreateForUser($user);

        return view('periodo.editperiod', [
            'user' => $user,
        ]);
    }

    public function updateperiod(StorePeriodExtraDaysRequest $request){
        $data = $request->validated();
        $period = periodo::findOrFail($data["id"]);
        $this->periodExtraDays->assign($period, $data["observacion"], (int) $data["cantidad"]);

        return view("periodo.listedit",[
            "extras" => $this->periodExtraDays->listWithUsers(),
            "resp" => true
        ]);
    }

    public function extras(){
        return view("periodo.listedit",[
            "extras" => $this->periodExtraDays->listWithUsers()
        ]);
    }

    public function checklist(){
        $users = User::all();

        return view("periodo.checklist",[
            "users" => $users
        ]);
    }

    public function checkuser($id)
    {
        return $this->currentUserPeriod($id);
    }

    public function prevd($id)
    {
        return $this->previousUserPeriod($id);
    }

    public function nextd($id)
    {
        return $this->nextUserPeriod($id);
    }

    /**
     * Shows another user's current annual period for administrative review.
     */
    public function currentUserPeriod($id)
    {
        return $this->renderUserPeriod($id, 0, true, true);
    }

    /**
     * Shows another user's previous annual period for administrative review.
     */
    public function previousUserPeriod($id)
    {
        return $this->renderUserPeriod($id, -1, true, false);
    }

    /**
     * Shows another user's next annual period for administrative review.
     */
    public function nextUserPeriod($id)
    {
        return $this->renderUserPeriod($id, 1, false, true);
    }

    private function renderUserPeriod($id, int $yearOffset, bool $canGoNext, bool $canGoPrevious)
    {
        $user = User::findOrFail($id);
        $period = $this->periodCalendar->findOrCreateForUser($user, $yearOffset);
        $calendarData = $this->periodCalendar->buildCalendarData($user, $period);

        return view('periodo.showuser', array_merge($calendarData, [
            'nextno' => $canGoNext,
            'prevno' => $canGoPrevious,
            'use' => $user,
        ]));
    }

    /**
     * Shows the annual specialty calendar with approved dispensa days marked.
     */
    public function anio()
    {
        $user = User::findOrFail(Auth::id());

        return view('periodo.estadoanual', $this->periodCalendar->buildAnnualSpecialtyCalendarData($user));
    }

    private function authorizationIcons(): array
    {
        return [
            sol::REJECTED => "far fa-times fa-2x",
            sol::PENDING => "far fa-clock",
            sol::APPROVED => "far fa-check fa-2x",
        ];
    }

}
