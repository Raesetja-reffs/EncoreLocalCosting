@extends('base')
@section('title', 'Costing')

@section('page')

<style>
    #excel-table td,
    #excel-table th {
        font-weight: 700;
    }
</style>

@php
    $colspan = count($lines);
    $colspan = 3 + $colspan;
    $totalMnyTerm = 0;
@endphp

<div class="h-100 d-flex flex-column">
    <div class='row encore-bg-dark text-white p-3 align-items-center'>
        <div class="col-lg-3 d-flex">
            <img src="{{asset('images/icon-dark-big.jpg')}}" alt="" style="border-radius: 50%; width: 50px;">

            <h2 class="px-3 text-uppercase">
                @if($editMode == 1)
                    {{ $header->strDescription }} V{{ $header->intVersion}}
                @else
                    {{ $header->strDescription }} V{{ $header->intVersion  + 1}}
                @endif
            </h2>
        </div>
        <div class="col-lg-7 d-inline-flex">
            <div class="col d-inline-flex">
                <label class="d-flex text-white text-nowrap align-items-center px-2" >DC</label> 
                <select id="selectDC" class="form-select">
                    <option></option>
                    @foreach ($DCs as $DC)
                        <option value="{{ $DC->ID }}">{{ $DC->DCName }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col d-inline-flex">
                <label class="d-flex text-white text-nowrap align-items-center px-2" >Deal Type</label> 
                <select id="selectDealType" class="form-select">
                    <option></option>
                    @foreach ($dealTypes as $dealType)
                        <option value="{{ $dealType->intDealTypeID }}">{{ $dealType->strDealName }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col d-inline-flex">
                <label class="d-flex text-white text-nowrap align-items-center px-2" >Date From</label> 
                <input class="form-control mx-2" type="date" id="dateFrom">
            </div>

            <div class="col d-inline-flex">
                <label class="d-flex text-white text-nowrap align-items-center px-2" >Date To</label> 
                <input class="form-control mx-2" type="date" id="dateTo">
            </div>

            @if($editMode == 1)
                <button type="button" class="btn btn-primary" id="btnUpdate">
                    UPDATE
                </button>
            @else
                <button type="button" class="btn btn-primary" id="btnRevise">
                    REVISE
                </button>
            @endif
        </div>

        <div class="col-lg-2 d-inline-flex flex-row-reverse">
            <a id="logout" class="text-nowrap p-1"><i class="fa fa-sign-out" aria-hidden="true"></i></a>
            <h4 class="text-uppercase text-nowrap px-3">WELCOME {{ Session::get('UserDisplayName') }}</h4>
        </div>
    </div>
    
    <div class="h-100 m-0 p-0">
        <table class="table table-bordered border border-dark" id="excel-table">
            <tbody>
                <tr>
                    <td class="text-nowrap encore-bg-grey">CASES PER PALLET</td>
                    <td class="text-nowrap encore-bg-grey">&nbsp;</td>
                    <td class="text-nowrap encore-bg-grey">&nbsp;</td>
                    @foreach ($lines as $line)
                        <td id="{{ $line->strProductCode }}_casesPerPallet" class="text-nowrap encore-bg-green text-center">{{ $line->CasesPerPallet }}</td>
                    @endforeach
                </tr>

                <tr>
                    <td class="text-nowrap encore-bg-grey">UNITS PER CASE</td>
                    <td class="text-nowrap encore-bg-grey">&nbsp;</td>
                    <td class="text-nowrap encore-bg-grey">&nbsp;</td>
                    @foreach ($lines as $line)
                        <td id="{{ $line->strProductCode }}_unitsPerCase" class="text-nowrap encore-bg-green text-center">{{ $line->UnitsPerCase }}</td>
                    @endforeach
                </tr>

                <tr>
                    <td colspan="{{ $colspan }}"></td> {{-- These are just spaces in the table --}}
                </tr>

                <tr>
                    <td class="text-nowrap encore-bg-grey">PRODUCT</td>
                    <td class="text-nowrap encore-bg-grey">&nbsp;</td>
                    <td class="text-nowrap encore-bg-grey">&nbsp;</td>
                    @foreach ($lines as $line)
                        <td class="text-nowrap encore-bg-green text-center">{{ $line->Description }}</td>
                    @endforeach
                </tr>

                <tr>
                    <td class="text-nowrap encore-bg-grey">SUPPLIER</td>
                    <td class="text-nowrap encore-bg-grey">&nbsp;</td>
                    <td class="text-nowrap encore-bg-grey">&nbsp;</td>
                    @foreach ($lines as $line)
                        <td class="text-nowrap encore-bg-green text-center">{{ $line->strSupplier }}</td>
                    @endforeach
                </tr>

                <tr>
                    <td class="text-nowrap encore-bg-grey">PACKAGING / UNIT</td>
                    <td class="text-nowrap encore-bg-grey">&nbsp;</td>
                    <td class="text-nowrap encore-bg-grey">&nbsp;</td>
                    @foreach ($lines as $line)
                        <td class="text-nowrap encore-bg-green text-center">{{ $line->UnitsPerCase }} x {{ $line->UnitSize }}{{ $line->UnitMeasure }}</td>
                    @endforeach
                </tr>

                <tr>
                    <td colspan="{{ $colspan }}"></td> {{-- These are just spaces in the table --}}
                </tr>

                <tr>
                    <td class="text-nowrap encore-bg-grey">LIST PRICE</td>
                    <td class="text-nowrap encore-bg-grey">&nbsp;</td>
                    <td class="text-nowrap encore-bg-grey">&nbsp;</td>
                    @foreach ($lines as $line)
                        <td class="text-nowrap m-0 p-0">
                            <input type="text" @if($editMode == 1) value= {{ number_format($line->mnyListPrice, env('ROUND_VALUE')) }} @else value="" @endif id="{{ $line->strProductCode }}_listPrice" class="form-control list-price" placeholder="Enter value">
                        </td>
                    @endforeach
                </tr>

                <tr>
                    <td class="text-nowrap encore-bg-grey">DISCOUNT %</td>
                    <td class="text-nowrap encore-bg-grey">&nbsp;</td>
                    <td class="text-nowrap encore-bg-grey">&nbsp;</td>
                    @foreach ($lines as $line)
                        <td class="text-nowrap m-0 p-0">
                            <input type="text" @if($editMode == 1) value={{ number_format($line->mnyListDiscount, env('ROUND_VALUE')) }} @else value="" @endif id="{{ $line->strProductCode }}_listDiscount" class="form-control list-discount" placeholder="Enter value">
                        </td>
                    @endforeach
                </tr>

                <tr>
                    <td class="text-nowrap encore-bg-grey">DISCOUNT LIST PRICE</td>
                    <td class="text-nowrap encore-bg-grey">&nbsp;</td>
                    <td class="text-nowrap encore-bg-grey">&nbsp;</td>
                    @foreach ($lines as $line)
                        <td id="{{ $line->strProductCode }}_discountListPrice" class="text-nowrap encore-bg-blue text-center">&nbsp;</td>
                    @endforeach
                </tr>

                <tr>
                    <td colspan="{{ $colspan }}"></td> {{-- These are just spaces in the table --}}
                </tr>

                <tr>
                    <td class="text-nowrap encore-bg-grey">MANUFACURED COST</td>
                    <td class="text-nowrap encore-bg-grey">&nbsp;</td>
                    <td class="text-nowrap encore-bg-grey">&nbsp;</td>
                    @foreach ($lines as $line)
                        <td class="text-nowrap m-0 p-0">
                            <input type="text" @if($editMode == 1) value={{ number_format($line->mnyManufacturedCost, env('ROUND_VALUE')) }} @else value="" @endif id="{{ $line->strProductCode }}_manufacturedCost" class="form-control manufactured-cost" placeholder="Enter value">
                        </td>
                    @endforeach
                </tr>

                {{-- <tr>
                    <td colspan="{{ $colspan }}"></td>
                </tr> --}}

                <tr>
                    <td class="text-nowrap encore-bg-grey">SETTLEMENT</td>
                    <td class="text-nowrap encore-bg-grey">&nbsp;</td>
                    <td class="text-nowrap encore-bg-grey">&nbsp;</td>
                    @foreach ($lines as $line)
                        <td class="text-nowrap m-0 p-0">
                            <input type="text" @if($editMode == 1) value={{ number_format($line->mnySettlement, env('ROUND_VALUE')) }} @else value="" @endif id="{{ $line->strProductCode }}_settlement" class="form-control settlement" placeholder="Enter value">
                        </td>
                    @endforeach
                </tr>

                <tr>
                    <td class="text-nowrap encore-bg-grey">NET PRODUCT COST</td>
                    <td class="text-nowrap encore-bg-grey">&nbsp;</td>
                    <td class="text-nowrap encore-bg-grey">&nbsp;</td>
                    @foreach ($lines as $line)
                        <td class="text-nowrap m-0 p-0">
                            <input type="text" @if($editMode == 1) value={{ number_format($line->mnyNetProductCost, env('ROUND_VALUE')) }} @else value="" @endif id="{{ $line->strProductCode }}_netProductCost" class="form-control net-product-cost" placeholder="Enter value">
                        </td>
                    @endforeach
                </tr>

                <tr>
                    <td colspan="{{ $colspan }}"></td> {{-- These are just spaces in the table --}}
                </tr>

                <tr>
                    <td class="text-nowrap encore-bg-grey">INBOUND</td>
                    <td id="inbound" class="text-nowrap encore-bg-grey text-center">{{ $header->intInbound }}</td>
                    <td class="text-nowrap encore-bg-grey">&nbsp;</td>
                    @foreach ($lines as $line)
                        <td id="{{ $line->strProductCode }}_inbound" class="text-nowrap encore-bg-blue text-center">&nbsp;</td>
                    @endforeach
                </tr>

                <tr>
                    <td class="text-nowrap encore-bg-grey">OUTBOUND</td>
                    <td id="outbound" class="text-nowrap encore-bg-grey text-center">{{ $header->intOutbound }}</td>
                    <td class="text-nowrap encore-bg-grey">&nbsp;</td>
                    @foreach ($lines as $line)
                        <td id="{{ $line->strProductCode }}_outbound" class="text-nowrap encore-bg-blue text-center">&nbsp;</td>
                    @endforeach
                </tr>

                <tr>
                    <td class="text-nowrap encore-bg-grey">WAREHOUSING</td>
                    <td id="warehousing" class="text-nowrap encore-bg-grey text-center">{{ $header->intWarehousing }}</td>
                    <td class="text-nowrap encore-bg-grey">&nbsp;</td>
                    @foreach ($lines as $line)
                        <td id="{{ $line->strProductCode }}_warehousing" class="text-nowrap encore-bg-blue text-center">&nbsp;</td>
                    @endforeach
                </tr>

                <tr>
                    <td colspan="{{ $colspan }}"></td> {{-- These are just spaces in the table --}}
                </tr>

                <tr>
                    <td class="text-nowrap encore-bg-grey">LANDED COST</td>
                    <td class="text-nowrap encore-bg-grey">&nbsp;</td>
                    <td class="text-nowrap encore-bg-grey">&nbsp;</td>
                    @foreach ($lines as $line)
                        <td id="{{ $line->strProductCode }}_landedCost" class="text-nowrap encore-bg-blue text-center">&nbsp;</td>
                    @endforeach
                </tr>

                <tr>
                    <td colspan="{{ $colspan }}"></td> {{-- These are just spaces in the table --}}
                </tr>

                {{-- Trade Terms Section --}}
                @foreach ($tradeTerms as $tradeTerm)
                @php
                    $totalMnyTerm += floatval($tradeTerm->mnyTerm);
                @endphp
                <tr>
                    <td class="text-nowrap encore-bg-grey text-uppercase">{{ $tradeTerm->strTerm }}</td>
                    <td class="text-nowrap encore-bg-grey">&nbsp;</td>
                    <td id="{{ $tradeTerm->intAutoId }}_TT" class="text-nowrap encore-bg-grey text-center">{{ number_format($tradeTerm->mnyTerm, env('ROUND_VALUE')) }}</td>
                    @foreach ($lines as $line)
                        <td id="{{ $line->strProductCode }}_{{ $tradeTerm->intAutoId }}_TT" class="text-nowrap encore-bg-blue text-center">&nbsp;</td>
                    @endforeach
                </tr>
                @endforeach
                {{-- End of Trade Terms Section --}}
                
                <tr>
                    <td class="text-nowrap encore-bg-grey">NET COST</td>
                    <td class="text-nowrap encore-bg-grey">&nbsp;</td>
                    <td id="netCost_TT" class="text-nowrap encore-bg-grey text-center">{{ number_format($totalMnyTerm, env('ROUND_VALUE')) }}</td>
                    @foreach ($lines as $line)
                        <td id="{{ $line->strProductCode }}_netCost" class="text-nowrap encore-bg-blue text-center">&nbsp;</td>
                    @endforeach
                </tr>

                <tr>
                    <td colspan="{{ $colspan }}"></td> {{-- These are just spaces in the table --}}
                </tr>

                <tr>
                    <td class="text-nowrap encore-bg-grey">GROSS MARGIN</td>
                    <td class="text-nowrap encore-bg-grey">&nbsp;</td>
                    <td class="text-nowrap encore-bg-grey">&nbsp;</td>
                    @foreach ($lines as $line)
                        <td id="{{ $line->strProductCode }}_grossMargin" class="text-nowrap encore-bg-blue text-center">&nbsp;</td>
                    @endforeach
                </tr>

                <tr>
                    <td class="text-nowrap encore-bg-grey">GROSS MARGIN %</td>
                    <td class="text-nowrap encore-bg-grey">&nbsp;</td>
                    <td class="text-nowrap encore-bg-grey">&nbsp;</td>
                    @foreach ($lines as $line)
                        <td id="{{ $line->strProductCode }}_grossMarginPercent" class="text-nowrap encore-bg-blue text-center">&nbsp;</td>
                    @endforeach
                </tr>

                <tr>
                    <td colspan="{{ $colspan }}"></td> {{-- These are just spaces in the table --}}
                </tr>

                <tr>
                    <td class="text-nowrap encore-bg-grey">UNIT COST EX VAT</td>
                    <td class="text-nowrap encore-bg-grey">&nbsp;</td>
                    <td class="text-nowrap encore-bg-grey">&nbsp;</td>
                    @foreach ($lines as $line)
                        <td id="{{ $line->strProductCode }}_unitCostExVat" class="text-nowrap encore-bg-blue text-center">&nbsp;</td>
                    @endforeach
                </tr>

                <tr>
                    <td class="text-nowrap encore-bg-grey">RSP</td>
                    <td class="text-nowrap encore-bg-grey">&nbsp;</td>
                    <td class="text-nowrap encore-bg-grey">&nbsp;</td>
                    @foreach ($lines as $line)
                        <td id="{{ $line->strProductCode }}_rsp" class="text-nowrap encore-bg-blue text-center">&nbsp;</td>
                    @endforeach
                </tr>

                <tr>
                    <td colspan="{{ $colspan }}"></td> {{-- These are just spaces in the table --}}
                </tr>

                <tr>
                    <td class="text-nowrap encore-bg-grey">RETAILER</td>
                    <td class="text-nowrap encore-bg-grey">&nbsp;</td>
                    <td id="retailer" class="text-nowrap encore-bg-grey text-center">{{ $header->mnyRetailPercentage }}</td>
                    @foreach ($lines as $line)
                        <td id="{{ $line->strProductCode }}_retailer" class="text-nowrap encore-bg-blue text-center">&nbsp;</td>
                    @endforeach
                </tr>

                <tr>
                    <td class="text-nowrap encore-bg-grey">DC</td>
                    <td class="text-nowrap encore-bg-grey">&nbsp;</td>
                    <td id="dc" class="text-nowrap encore-bg-grey text-center">{{ $header->mnyDCPercentage }}</td>
                    @foreach ($lines as $line)
                        <td id="{{ $line->strProductCode }}_dc" class="text-nowrap encore-bg-blue text-center">&nbsp;</td>
                    @endforeach
                </tr>

                <tr>
                    <td colspan="{{ $colspan }}"></td> {{-- These are just spaces in the table --}}
                </tr>

                <tr>
                    <td colspan="3" class="text-nowrap encore-bg-grey text-center">ENCORE BUDGETED MARGIN</td>
                    @foreach ($lines as $line)
                        <td class="text-nowrap m-0 p-0">
                            <input type="text" @if($editMode == 1) value={{ number_format($line->mnyBudgetedMargin, env('ROUND_VALUE')) }}@else value="" @endif id="{{ $line->strProductCode }}_budgetedMargin" class="form-control budgeted-margin" placeholder="Enter value">
                        </td>
                    @endforeach
                </tr>

                <tr>
                    <td colspan="3" class="text-nowrap encore-bg-grey text-center">DEVIATION ENCORE GP FROM ABOVE TO BUDGETED GB</td>
                    @foreach ($lines as $line)
                        <td id="{{ $line->strProductCode }}_deviation" class="text-nowrap encore-bg-blue text-center">&nbsp;</td>
                    @endforeach
                </tr>
            </tbody>
        </table>
    </div>
</div>

@endsection

@section('scripts')

<script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $(document).on('focus', ':input', function() {
        $(this).attr('autocomplete', 'off');
    });

    $(document).ready(function() {

        $("#selectDC").select2({
            theme: 'bootstrap-5',
        })

        $("#selectDealType").select2({
            theme: 'bootstrap-5',
        })

        $('.list-price, .list-discount, .manufactured-cost, .settlement, .net-product-cost, .budgeted-margin').on('input', function(){
            var strProductCode= $(this).attr('id');
            var strProductCode = strProductCode.split('_')[0]
            doCalculations(strProductCode);
        });

        function doCalculations(strProductCode){
            calculateLineDiscount(strProductCode);
            calculateInbound(strProductCode);
            calculateOutbound(strProductCode);
            calculateWarehousing(strProductCode);
            calculateLandedCost(strProductCode);
            calculateTradeTerm(strProductCode);
            calculateGrossMargin(strProductCode);
            calculateUnitCost(strProductCode);
            calculateRSP(strProductCode);
            calculateDC(strProductCode);
            calculateDeviation(strProductCode);
        };

        function calculateLineDiscount(strProductCode) {
            var listPrice = $("#" + strProductCode + "_listPrice").val();
            var listDiscount = $("#" + strProductCode + "_listDiscount").val();
            var discountListPrice = listPrice - (listPrice * listDiscount / 100);

            $("#" + strProductCode + "_discountListPrice").text((discountListPrice).toFixed({{ env('ROUND_VALUE') }}));
        };

        function calculateInbound(strProductCode){
            var casesPerPallet = $("#" + strProductCode + "_casesPerPallet").text();
            var inbound =  $("#inbound").text();
            var total = (inbound / {{ env('COST_VALUE') }} / casesPerPallet);
            $("#" + strProductCode + "_inbound").text((total).toFixed({{ env('ROUND_VALUE') }}));
        };

        function calculateOutbound(strProductCode){
            var casesPerPallet = $("#" + strProductCode + "_casesPerPallet").text();
            var outbound =  $("#outbound").text();
            var total = (outbound / {{ env('COST_VALUE') }} / casesPerPallet);
            $("#" + strProductCode + "_outbound").text((total).toFixed({{ env('ROUND_VALUE') }}));
        };

        function calculateWarehousing(strProductCode){
            var casesPerPallet = $("#" + strProductCode + "_casesPerPallet").text();
            var warehousing =  $("#warehousing").text();
            var total = (warehousing / casesPerPallet);
            $("#" + strProductCode + "_warehousing").text((total).toFixed({{ env('ROUND_VALUE') }}));
        };

        function calculateLandedCost(strProductCode) {
            var netProductCost = parseFloat($("#" + strProductCode + "_netProductCost").val()) || 0;
            var inbound = parseFloat($("#" + strProductCode + "_inbound").text()) || 0;
            var outbound = parseFloat($("#" + strProductCode + "_outbound").text()) || 0;
            var warehousing = parseFloat($("#" + strProductCode + "_warehousing").text()) || 0;

            var landedCost = (netProductCost + inbound + outbound + warehousing);
            $("#" + strProductCode + "_landedCost").text((landedCost).toFixed({{ env('ROUND_VALUE') }}));
        };

        function calculateTradeTerm(strProductCode){
            var tradeTerms = {!! json_encode($tradeTerms) !!};
            var discountListPrice = parseFloat($("#" + strProductCode + "_discountListPrice").text());
            var netCost = 0;

            $.each(tradeTerms, function(index, tradeTerm) {
                var term = tradeTerm.strTerm;
                var id = tradeTerm.intAutoId
                var termPercentage = parseFloat($("#" + (id) + "_TT").text());
                var total = ((termPercentage/100) * discountListPrice);

                $("#" + strProductCode + "_" + (id) + "_TT").text((total).toFixed({{ env('ROUND_VALUE') }}));
                netCost += parseFloat(total);
            });
            
            landedCost = parseFloat($("#" + strProductCode+ "_landedCost").text());

            netCost += landedCost;
            $("#" + strProductCode + "_netCost").text((netCost).toFixed({{ env('ROUND_VALUE') }}));
        };

        function calculateGrossMargin(strProductCode){
            var discountListPrice = parseFloat($("#" + strProductCode + "_discountListPrice").text());
            var netCost = parseFloat($("#" + strProductCode + "_netCost").text());
            var grossMargin = (discountListPrice - netCost).toFixed({{ env('ROUND_VALUE') }});
            var grossMarginPercent = ((grossMargin / discountListPrice)*100).toFixed({{ env('ROUND_VALUE') }});

            $("#" + strProductCode + "_grossMargin").text(grossMargin);
            $("#" + strProductCode + "_grossMarginPercent").text(grossMarginPercent);
        };

        function calculateUnitCost(strProductCode){
            var discountListPrice = parseFloat($("#" + strProductCode + "_discountListPrice").text());
            var total = (discountListPrice / 12).toFixed({{ env('ROUND_VALUE') }});

            $("#" + strProductCode + "_unitCostExVat").text(total);
        };

        function calculateRSP(strProductCode){
            var unitCostExVat = parseFloat($("#" + strProductCode + "_unitCostExVat").text());
            var retailer = parseFloat($("#retailer").text());
            var total = (unitCostExVat/(1-(retailer/100))*1.15).toFixed({{ env('ROUND_VALUE') }});

            $("#" + strProductCode + "_rsp").text(total);
        };

        function calculateDC(strProductCode){
            var discountListPrice = parseFloat($("#" + strProductCode + "_discountListPrice").text());
            var dc = parseFloat($("#dc").text());
            var total = (discountListPrice*(1+(dc/100))).toFixed({{ env('ROUND_VALUE') }});

            $("#" + strProductCode + "_dc").text(total);
        };

        function calculateDeviation(strProductCode){
            var budgetedMargin = parseFloat($("#" + strProductCode + "_budgetedMargin").val());
            var grossMarginPercent = parseFloat($("#" + strProductCode + "_grossMarginPercent").text());
            var total = (grossMarginPercent - budgetedMargin).toFixed({{ env('ROUND_VALUE') }});

            $("#" + strProductCode + "_deviation").text(total);
            
        };

        $("#btnUpdate").click(function(){
            var headerId = "{!! $header->intAutoId  !!}";
            var lines = {!! json_encode($lines) !!};
            var strProductCodes = lines.map(item => item.strProductCode);
            
            var headerData = getHeaderData();
            var lineData = getLineData(strProductCodes);

            updateCostingSheet(headerId, headerData, lineData);

        });

        $("#btnRevise").click(function(){
            var headerId = "{!! $header->intAutoId  !!}";
            var lines = {!! json_encode($lines) !!};
            var strProductCodes = lines.map(item => item.strProductCode);
            
            var headerData = getHeaderData();
            var lineData = getLineData(strProductCodes);

            reviseCostingSheet(headerId, headerData, lineData);

        });

        function getHeaderData(){
            var data = [{
                "inbound": parseFloat($("#inbound").text()),
                "outbound": parseFloat($("#outbound").text()),
                "warehousing": parseFloat($("#warehousing").text()),
                "retailPercentage": parseFloat($("#retailer").text()),
                "dcPercentage": parseFloat($("#dc").text()),
            }];

            return data;
        }

        function getLineData(strProductCodes) {
            var data = [];

            strProductCodes.forEach(function(strProductCode) {
                var item = {
                    "strProductCode": strProductCode,
                    "listPrice": parseFloat($("#" + strProductCode + "_listPrice").val()),
                    "listDiscount": parseFloat($("#" + strProductCode + "_listDiscount").val()),
                    "manufacturedCost": parseFloat($("#" + strProductCode + "_manufacturedCost").val()),
                    "settlement": parseFloat($("#" + strProductCode + "_settlement").val()),
                    "netProductCost": parseFloat($("#" + strProductCode + "_netProductCost").val()),
                    "budgetedMargin": parseFloat($("#" + strProductCode + "_budgetedMargin").val())
                };
                data.push(item);
            });

            return data;
        }

        function updateCostingSheet(headerId, headerData, lineData){
            $.ajax({
                url: '{!!url("/postUpdateCostingSheet")!!}',
                type: "POST", 
                data: {
                    headerId: headerId,
                    headerData: headerData,
                    lineData: lineData,
                    intDC: $('#selectDC').val(),
                    intDealType: $('#selectDealType').val(),
                    dteValidFrom: $('#dateFrom').val(),
                    dteValidTo: $('#dateTo').val(),
                },
                success: function(data) {
                    window.location.href = '{!!url("/")!!}';
                }
            });
        };

        function reviseCostingSheet(headerId, headerData, lineData){
            $.ajax({
                url: '{!!url("/postReviseCostingSheet")!!}',
                type: "POST", 
                data: {
                    headerId: headerId,
                    headerData: headerData,
                    lineData: lineData,
                    intDC: $('#selectDC').val(),
                    intDealType: $('#selectDealType').val(),
                    dteValidFrom: $('#dateFrom').val(),
                    dteValidTo: $('#dateTo').val(),
                },
                success: function(data) {
                    window.location.href = '{!!url("/")!!}';
                }
            });
        };

        // This section deals with the hover and double click
        $('.list-price, .list-discount, .manufactured-cost, .settlement, .net-product-cost, .budgeted-margin').on('mouseenter mouseleave dblclick', function(event) {
            var value;
            var lines = {!! json_encode($lines) !!};
            var input = $(this).attr('id');
            var strProductCode = input.split('_')[0];
            var type = input.split('_')[1];

            switch (type) {
                case 'listPrice':
                    value = lines.find(line => line.strProductCode === strProductCode)?.mnyListPrice;
                    break;
                case 'listDiscount':
                    value = lines.find(line => line.strProductCode === strProductCode)?.mnyListDiscount;
                    break;
                case 'manufacturedCost':
                    value = lines.find(line => line.strProductCode === strProductCode)?.mnyManufacturedCost;
                    break;
                case 'settlement':
                    value = lines.find(line => line.strProductCode === strProductCode)?.mnySettlement;
                    break;
                case 'netProductCost':
                    value = lines.find(line => line.strProductCode === strProductCode)?.mnyNetProductCost;
                    break;
                case 'budgetedMargin':
                    value = lines.find(line => line.strProductCode === strProductCode)?.mnyBudgetedMargin;
                    break;
                default:
                    value = null;
                    break;
            }

            if (event.type === 'mouseenter') {
                value = parseFloat(value).toFixed({{ env('ROUND_VALUE') }})
                $(this).attr("placeholder", value);
            } else if (event.type === 'mouseleave') {
                $(this).attr("placeholder", "Enter value");
            } else if (event.type === 'dblclick') {
                // Round the value to 2 decimal places using toFixed and then set it as the input value
                if (value !== null) {
                    value = parseFloat(value).toFixed({{ env('ROUND_VALUE') }});
                    $(this).val(value);
                }
                doCalculations(strProductCode);
            }
        });

    });

</script>

@endsection