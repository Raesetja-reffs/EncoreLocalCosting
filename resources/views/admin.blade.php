@extends('base')
@section('title', 'Admin')

@section('page')
<style>
    #gridCostingSheet {
        height: 100% !important;
        max-height: 100% !important;
    }

    .customPadding {
        padding: 0px !important;
    }
</style>

<div class="container-fluid h-100 d-flex flex-column">
    <div class='row encore-bg-dark p-3'>
        <div class="col">
            <div class="d-flex">
                <img src="{{asset('images/icon-dark-big.jpg')}}" alt="" style="border-radius: 50%; width: 50px;">
                <h2 class="text-center px-3 mb-0">ADMIN</h2>
            </div>
        </div>
        <div class="col">
            <div class="d-flex justify-content-end">
                <h4 class="text-uppercase text-nowrap px-3">WELCOME {{ Session::get('UserDisplayName') }}</h4>
                <a id="logout" class="text-nowrap p-1 encore-text-red"><i class="fa fa-sign-out" aria-hidden="true"></i></a>
            </div>
        </div>

    </div>
    <div class="row flex-grow-1 py-3">
        <div class="col-lg">
            <div id="gridTradeTerms"></div>
        </div>
        <div class="col-lg">
            <h5>WAREHOUSE COSTS</h5>
            <div class=" bg-light p-2">
                <div class="form-group mb-2">
                    <label for="inputInbound">Inbound</label>
                    <input type="number" class="form-control" id="inputInbound"/>
                </div>
                <div class="form-group mb-2">
                    <label for="inputOutbound">Outbound</label>
                    <input type="number" class="form-control" id="inputOutbound"/>
                </div>
                <div class="form-group mb-2">
                    <label for="inputWarehousing">Warehousing</label>
                    <input type="number" class="form-control" id="inputWarehousing"/>
                </div>
                <div class="form-group mb-2">
                    <label for="inputRetailPercentage">Retail Percentage</label>
                    <input type="number" class="form-control" id="inputRetailPercentage"/>
                </div>
                <div class="form-group mb-2">
                    <label for="inputDCPercentage">DC Percentage</label>
                    <input type="number" class="form-control" id="inputDCPercentage"/>
                </div>
                <div class="w-100" id="btnUpdateWarehouseCosts"></div>
            </div>
        </div>
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

    var data = [];

    $(document).ready(function() {
        // Initilaze the datagrids whith Empty DataSet
        const gridTradeTerms = $('#gridTradeTerms').dxDataGrid({
            dataSource: getTradeTerms(), //as json
            showBorders: true,
            hoverStateEnabled: true,
            filterRow: { visible: true },
            filterPanel: { visible: true },
            headerFilter: { visible: true },
            allowColumnResizing: true,
            columnAutoWidth: true,
            scrolling: {
                mode: 'virtual', // Enable infinite scrolling
            },
            paging:{
                pageSize: 10,
            },   
            editing: {
                mode: 'batch',
                allowAdding: true,
                allowUpdating: true,
                allowDeleting: true,
            }, 
            columns: [
                {
                    dataField: 'intAutoId',
                    caption: 'ID',
                    allowEditing: false,
                    visible: false,
                },
                {
                    dataField: 'strTerm',
                    caption: 'Term',
                },
                {
                    dataField: 'mnyTerm',
                    caption: 'Value',
                    dataType: 'number',
                    format: {
                        type: "fixedPoint",
                        precision: 2
                    },
                },
            ],
            onRowDblClick: function (e) {
                // console.debug(e);
            },
            onInitNewRow: function(e) {
                // console.debug(e);
            },
            onRowInserting: function(e) {
                // console.log(e);
                $.ajax({
                    url: '{!!url("/postTradeTermsCrud")!!}',
                    type: "POST", 
                    data: {
                        strTerm: e.data.strTerm,
                        mnyTerm: e.data.mnyTerm,
                        command: 'CREATE',
                    },
                    success: function (data) {
                        getTradeTerms();
                    }
                });
            },
            onRowInserted: function(e) {
                // console.debug(e);
            },
            onRowUpdating: function(e) {
                $.ajax({
                    url: '{!!url("/postTradeTermsCrud")!!}',
                    type: "POST", 
                    data: {
                        intAutoId: e.oldData.intAutoId,
                        strTerm: e.newData.strTerm || e.oldData.strTerm,
                        mnyTerm: e.newData.mnyTerm || e.oldData.mnyTerm,
                        command: 'UPDATE',
                    },
                    success: function (data) {
                        getTradeTerms();
                    }
                });
            },
            onRowRemoving: function(e) {
                $.ajax({
                    url: '{!!url("/postTradeTermsCrud")!!}',
                    type: "POST", 
                    data: {
                        intAutoId: e.data.intAutoId,
                        command: 'DELETE',
                    },
                    success: function (data) {
                        getTradeTerms();
                    }
                });
            },
            onToolbarPreparing: function (e) {
				// Create a custom header on the left side
				e.toolbarOptions.items.unshift(
					{
						location: 'before',
						template: function () {
							return $('<h5>').text('TRADE TERMS');
						}
					}
				);
			}
        }).dxDataGrid('instance');

        $('#btnUpdateWarehouseCosts').dxButton({
            stylingMode: 'outlined',
            text: 'REVISE',
            icon: "fa fa-history", // Add an icon
            type: 'default',
            onClick() {
                $.ajax({
                    url: '{!!url("/postWarehouseCostsCrud")!!}',
                    type: "POST", 
                    data: {
                        intInbound: $("#inputInbound").val(),
                        intOutbound: $("#inputOutbound").val(),
                        intWarehousing: $("#inputWarehousing").val(),
                        mnyDCPercentage: $("#inputDCPercentage").val(),
                        mnyRetailPercentage: $("#inputRetailPercentage").val(),
                        command: 'CREATE',
                    },
                    success: function (data) {
                        alert("Updated!");
                        getWarehouseCosts();
                    }
                });
            },
        });

        function getTradeTerms(){
            $.ajax({
                url: '{!!url("/postTradeTermsCrud")!!}',
                type: "POST", 
                data: {
                    command: 'READ',
                },
                success: function(data) {
                    // console.log(data)
                    gridTradeTerms.option('dataSource', data);
                    gridTradeTerms.refresh();
                }
            });
        }

        getWarehouseCosts();

        function getWarehouseCosts(){
            $.ajax({
                url: '{!!url("/postWarehouseCostsCrud")!!}',
                type: "POST", 
                data: {
                    command: 'READ',
                },
                success: function(data) {
                    console.log(data)
                    const intInbound = data[0]["intInbound"];
                    const intOutbound = data[0]["intOutbound"];
                    const intWarehousing = data[0]["intWarehousing"];
                    const mnyDCPercentage = data[0]["mnyDCPercentage"];
                    const mnyRetailPercentage = data[0]["mnyRetailPercentage"];

                    $("#inputInbound").val(intInbound);
                    $("#inputOutbound").val(intOutbound);
                    $("#inputWarehousing").val(intWarehousing);
                    $("#inputDCPercentage").val(mnyDCPercentage);
                    $("#inputRetailPercentage").val(mnyRetailPercentage);
                    
                }
            });
        }

    });
</script>

@endsection