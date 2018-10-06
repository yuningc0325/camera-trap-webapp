﻿import { Component } from '@angular/core';

@Component({
    selector: 'app-root',
    templateUrl: './app.component.html'
})

export class AppComponent {
    source: any =
    {
        dataType: 'xml',
        dataFields: [
            { name: 'SupplierName', type: 'string' },
            { name: 'Quantity', type: 'number' },
            { name: 'OrderDate', type: 'date' },
            { name: 'OrderAddress', type: 'string' },
            { name: 'Freight', type: 'number' },
            { name: 'Price', type: 'number' },
            { name: 'City', type: 'string' },
            { name: 'ProductName', type: 'string' },
            { name: 'Address', type: 'string' }
        ],
        url: '../sampledata/orderdetailsextended.xml',
        root: 'DATA',
        record: 'ROW'
    };

    dataAdapter: any = new jqx.dataAdapter(this.source);

    columns: any[] =
    [
        { text: 'Supplier Name', cellsAlign: 'center', align: 'center', dataField: 'SupplierName', width: 300 },
        { text: 'Product', cellsAlign: 'center', align: 'center', dataField: 'ProductName', width: 300 },
        { text: 'Quantity', dataField: 'Quantity', cellsFormat: 'd2', aggregates: ['avg', 'min', 'max'], cellsAlign: 'center', align: 'center', width: 120 },
        { text: 'Price', dataField: 'Price', cellsFormat: 'c2', align: 'center', cellsAlign: 'center', aggregates: ['sum', 'min', 'max'] }
    ];
}
