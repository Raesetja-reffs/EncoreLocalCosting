@extends('base')
@section('title', 'Dashboard')

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
                <h2 class="text-center px-3 mb-0">LOCAL COSTING</h2>
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
        <div class="col-lg-12">
            <div id="gridCostingSheet"></div>
        </div>
    </div>
</div>

<!-- createSheetModal -->
<div class="modal fade" id="createSheetModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="createSheetModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="createSheetModalLabel">CREATE NEW COSTING SHEET</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="closeCreateSheetModal"></button>
            </div>
            <div class="modal-body">
                <div class="border bg-light p-3 h-100">
                    <div class="form-group mb-3">
                        <label for="inputProduct">Costing Sheet Name</label>
                        <input class="form-control" id="inputSheetName"/>
                    </div>

                    <div class="form-group mb-3">
                        <label for="inputProduct">Product</label>
                        <div class="col-12 d-flex">
                            <input class="form-control me-2" id="inputProduct"/>
                            <div class="" id="btnProductSearch"></div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-lg-5">
                            <div id='gridProducts'></div>
                        </div>
                        <div class="col-lg-2 justify-content-center">
                            <div class="w-100 my-3" id="btnLoadProd"></div>
                            <div class="w-100 my-3" id="btnUnloadProd"></div>
                        </div>
                        <div class="col-lg-5">
                            <div id='gridLoadProducts'></div>
                        </div>
                    </div>
    
                    
                </div>
            </div>
            <div class="modal-footer">
                <div class="" id="btnCreateSheet"></div>
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
        const gridCostingSheet = $('#gridCostingSheet').dxDataGrid({
            dataSource: getUserCostingSheetsHeaders(), //as json
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
            toolbar: {
                items: [
                    {
                        location: "before",
                        widget: "dxButton",
                        options: {
                            text: "New Sheet",
                            icon: "fa fa-plus", // Add an icon
                            onClick: function(e) {
                                // Handle the button click event
                                $('#createSheetModal').modal('show'); // Show the Bootstrap modal
                            }
                        }
                    }
                ]
            },
            columns: [
                {
                    width: 20,
                },
                {
                    width: 35,
                    cellTemplate: function (container, options) {
                        container.addClass("customPadding");
                        $("<div>")
                            .dxButton({
                                icon: "fa fa-edit",
                                text: "",
                                onClick: function(e) {
                                    window.location.href = '{!!url("/costing")!!}/' + options.data.intAutoId + '/edit';
                                }
                            })
                            .appendTo(container);
                    }
                },
                {
                    width: 35,
                    cellTemplate: function (container, options) {
                        container.addClass("customPadding");
                        $("<div>")
                            .dxButton({
                                icon: "fa fa-history",
                                text: "",
                                onClick: function(e) {
                                    window.location.href = '{!!url("/costing")!!}/' + options.data.intAutoId;
                                }
                            })
                            .appendTo(container);
                    }
                },
                {
                    dataField: 'intAutoId',
                    caption: 'ID',
                },
                {
                    dataField: 'strDescription',
                    caption: 'Description',
                    groupIndex: 0
                },
                {
                    dataField: 'intVersion',
                    caption: 'Version',
                },
                {
                    dataField: "dtmCreated",
                    caption: "Time Created",
                    dataType: "datetime",
                    // calculateCellValue: function(data) {
                    //     // Assuming 'data.dtmCreated' is your API's date object
                    //     const apiDate = new Date(data.dtmCreated.date); 

                    //     return apiDate;
                    // },
                },
                {
                    width: 20,
                },
            ],
            onRowDblClick: function (e) {
                console.debug(e);
            },
            onInitNewRow: function(e) {
                console.debug(e);
            },
            onRowInserting: function(e) {
                console.debug(e);
            },
            onRowInserted: function(e) {
                console.debug(e);
            },
            onRowUpdating: function(e) {
                console.debug(e);
            },
        }).dxDataGrid('instance');
        
        const gridProducts = $('#gridProducts').dxDataGrid({
            dataSource: data, //as json
            showBorders: true,
            hoverStateEnabled: true,
            allowColumnResizing: true,
            columnAutoWidth: true,
            scrolling: {
                rowRenderingMode: 'infinite',
            },
            paging:{
                pageSize: 8,
            },     
            selection: {
                mode: 'multiple',
            },         
            columns: [
                {
                    dataField: 'Code',
                    caption: 'ID',
                },{
                    dataField: 'Description',
                    caption: 'Description',
                }
            ],
        }).dxDataGrid('instance');
        
        const gridLoadProducts = $('#gridLoadProducts').dxDataGrid({
            dataSource: data, //as json
            showBorders: true,
            hoverStateEnabled: true,
            allowColumnResizing: true,
            columnAutoWidth: true,
            scrolling: {
                rowRenderingMode: 'infinite',
            },
            paging:{
                pageSize: 8,
            },     
            selection: {
                mode: 'multiple',
            },         
            columns: [
                {
                    dataField: 'Code',
                    caption: 'ID',
                },{
                    dataField: 'Description',
                    caption: 'Description',
                }
            ],
        }).dxDataGrid('instance');

        // Load Costing Sheets for user
        function getUserCostingSheetsHeaders(){
            $.ajax({
                url: '{!!url("/getUserCostingSheetsHeaders")!!}',
                type: "GET",
                data: {
                },
                success: function(data) {
                    console.log(data);
                    gridCostingSheet.option('dataSource', data);
                    gridCostingSheet.refresh();
                }
            });
        };

        // Search for products
        function getProducts(productDesc){
            $.ajax({
                url: '{!!url("/getProducts")!!}',
                type: "GET",
                data: {
                    productDesc: productDesc,
                },
                success: function(data) {
                    // Filter out rows from data that exist in gridLoadProducts by 'Code'
                    var existingLoadProducts = gridLoadProducts.option("dataSource");
                    var filteredData = data.filter(function(product) {
                        return !existingLoadProducts.some(function(loadProduct) {
                            return product.Code === loadProduct.Code;
                        });
                    });

                    gridProducts.option('dataSource', filteredData);
                    gridProducts.refresh();
                    gridProducts.clearSelection();
                }
            });
        };

        // Button Clicks

        $('#btnProductSearch').dxButton({
            stylingMode: 'outlined',
            text: 'Search',
            type: 'default',
            icon: "fa fa-search", // Add an icon
            width: '150px',
            height: '37px',
            onClick() {
                var productDesc = $("#inputProduct").val();
                getProducts(productDesc);
            },
        });

        $('#btnLoadProd').dxButton({
            stylingMode: 'outlined',
            text: 'Add',
            icon: "fa fa-plus", // Add an icon
            type: 'success',
            onClick() {

                // DevExpress.ui.notify('The Outlined button was clicked');

                // Get selected rows from the gridProducts
                var selectedProducts = gridProducts.getSelectedRowsData();

                // Get the existing data of gridLoadProducts
                var existingLoadProducts = gridLoadProducts.option("dataSource");

                // Merge selected rows into existing data of gridLoadProducts
                var newLoadProductsData = existingLoadProducts.concat(selectedProducts);

                // Update the dataSource of gridLoadProducts with the merged data
                gridLoadProducts.option("dataSource", newLoadProductsData);

                // Remove selected rows from the gridProducts dataSource
                var currentProductsData = gridProducts.option("dataSource");
                for (var i = 0; i < selectedProducts.length; i++) {
                    var index = currentProductsData.indexOf(selectedProducts[i]);
                    if (index !== -1) {
                        currentProductsData.splice(index, 1);
                    }
                }

                // Update the dataSource of gridProducts with the modified data
                gridProducts.option("dataSource", currentProductsData);

                // Refresh both grids to display the updated data
                gridLoadProducts.refresh();
                gridProducts.refresh();
            },
        });

        $('#btnUnloadProd').dxButton({
            stylingMode: 'outlined',
            text: 'Remove',
            icon: "fa fa-subtract", // Add an icon
            type: 'danger',
            onClick() {
                // Get selected rows from the gridLoadProducts
                var selectedLoadProducts = gridLoadProducts.getSelectedRowsData();

                // Get the existing data of gridProducts
                var existingProductsData = gridProducts.option("dataSource");

                // Merge selected rows into existing data of gridProducts
                var newProductsData = existingProductsData.concat(selectedLoadProducts);

                // Update the dataSource of gridProducts with the merged data
                gridProducts.option("dataSource", newProductsData);

                // Remove selected rows from the gridLoadProducts dataSource
                var currentLoadProductsData = gridLoadProducts.option("dataSource");
                for (var i = 0; i < selectedLoadProducts.length; i++) {
                    var index = currentLoadProductsData.indexOf(selectedLoadProducts[i]);
                    if (index !== -1) {
                        currentLoadProductsData.splice(index, 1);
                    }
                }

                // Update the dataSource of gridLoadProducts with the modified data
                gridLoadProducts.option("dataSource", currentLoadProductsData);

                // Refresh both grids to display the updated data
                gridProducts.refresh();
                gridLoadProducts.refresh();
            },
        });

        $('#btnCreateSheet').dxButton({
            stylingMode: 'outlined',
            text: 'Create',
            type: 'success',
            icon: "fa fa-plus", // Add an icon
            height: '37px',
            onClick() {
                // Get data source of the gridLoadProducts
                var loadProductsDataSource = gridLoadProducts.option("dataSource");

                // Extract Codes from the data source and create a comma-separated string
                var products = loadProductsDataSource.map(function(product) {
                    return product.Code; // Adjust the property name based on your data structure
                });

                var codes = products.join(",");

                $.ajax({
                    url: '{!!url("/createCostingSheet")!!}',
                    type: "POST",
                    data: {
                        description: $("#inputSheetName").val(),
                        products: codes,
                    },
                    success: function(data) {
                        console.log(data)
                        // getUserCostingSheetsHeaders();
                        // $("#inputSheetName").val("");
                        // $("#inputProduct").val("");
                        // gridProducts.option("dataSource", []);
                        // gridLoadProducts.option("dataSource", []);

                        // gridProducts.refresh();
                        // gridLoadProducts.refresh();

                        // $("#closeCreateSheetModal").click();
                        window.location.href = '{!!url("/costing")!!}/' + data[0]["headerId"] + '/edit';
                    }
                });
            },
        });

    });
</script>

@endsection