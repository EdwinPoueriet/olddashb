jsGrid.sortStrategies.money = function (val1, val2) {
  const a = Number(val1.replace(/[^0-9\.]+/g, ""))
  const b = Number(val2.replace(/[^0-9\.]+/g, ""))
  return a - b;
}

const receipt_detail = function (config) {
  jsGrid.Field.call(this, config);
};

receipt_detail.prototype = new jsGrid.Field({

  align: "center", // redefine general property 'align'

  itemTemplate: function (value) {
    return '<a target="_blank" href="/receiptdetail/' + value + '">Ver</a>'
  }

});

jsGrid.fields.receipt_detail = receipt_detail;


const order_detail = function (config) {
  jsGrid.Field.call(this, config);
};

order_detail.prototype = new jsGrid.Field({

  align: "center", // redefine general property 'align'

  itemTemplate: function (value) {
    return '<a target="_blank" href="/orderdetail/' + value + '">Ver</a>'
  }

});

jsGrid.fields.order_detail = order_detail;


const return_detail = function (config) {
  jsGrid.Field.call(this, config);
};

return_detail.prototype = new jsGrid.Field({

  align: "center", // redefine general property 'align'

  itemTemplate: function (value) {
    return '<a target="_blank" href="/returndetail/' + value + '">Ver</a>'
  }

});

jsGrid.fields.return_detail = return_detail;


const view_location = function (config) {
  jsGrid.Field.call(this, config);
};

view_location.prototype = new jsGrid.Field({
  align: "center", // redefine general property 'align'
  itemTemplate: function (value) {
    if (value) {
      var location = value.split("|");
      if (location[1] === ',' || location[1] === '0,0') {
        return '<a target="_blank" href="https://www.google.com/maps?q=loc:' + location[0] + '">Ver</a>'
      }

      return '<a target="_blank" href="https://www.google.com/maps/dir/' + location[0] + '/' + location[1] + '">Ver</a>'
    } else {
      return 'N/A'
    }

  }

});

jsGrid.fields.view_location = view_location;


const status = function (config) {
  jsGrid.Field.call(this, config);
};

status.prototype = new jsGrid.Field({

  align: "center", // redefine general property 'align'

  itemTemplate: function (value, item) {
    // return value === "1" ? "h" : "";
    var editBt = $('<input class="jsgrid-button jsgrid-update-button" type="button" tittle="edit">')
    .on('click', function (e) {
      var r = confirm("Esta seguro de querer cambiar este valor?");
      if(r == true)
      {
        console.log("orden_id: " + item['order_id'])
        console.log("New value: " + value == "1" ? 0 : 1 )
        console.log(value == "1" ? 0 : 1)
      }
    })
    return editBt;
  }

});

jsGrid.fields.status = status;

