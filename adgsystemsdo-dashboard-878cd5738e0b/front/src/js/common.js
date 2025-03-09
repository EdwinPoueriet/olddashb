/**
 * Created by nelson on 18/04/17.
 */
export function sortNumber (field) {
  return function(ascending) {
    return function(a, b) {

      let A = moneyToNumber(a[field]);
      let B =  moneyToNumber(b[field]);
      if (ascending)
        return A >= B?1:-1;
      return A <= B?1:-1;
    }
  }
}

export function moneyToNumber(val) {
  return Number(val.replace(/[^0-9\.]+/g,""));
}

export function formatMoney(value, nozeros = null) {
  if (!isNaN(value)) {
    value = parseFloat(value)
  }

  let haha = nozeros ? value.toFixed(0) : value.toFixed(2)
  return haha.replace(/./g, function(c, i, a) {
    return i && c !== "." && ((a.length - i) % 3 === 0) ? ',' + c : c;
  });
}

export function headerRight(label) { return '<div style="text-align: right">'+label+'</div>' }