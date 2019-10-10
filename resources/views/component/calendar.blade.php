<div class="row">
    <div class="col-12">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>mes</th>
                    @for($i = 0; $i < 31; $i++)
                        <th>{{$i}}</th>
                    @endfor
                </tr>
            </thead>
            <tbody>
                @foreach($period as $months)
                    <tr>
                    @foreach($month["days"] as $days)
                        <td>

                        </td>
                    @endforeach
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
