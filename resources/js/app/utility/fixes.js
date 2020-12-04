$.fn.serializeObject = function() {
    let o = {};
    let a = this.serializeArray();
    $.each(a, function() {
        let name = this.name.replace(/(?=\S)(\[\]$)/, '').replace(/(?=\S)(-)(?<=\S)/, '_');
        if (o[name]) {
            if (!o[name].push) {
                o[name] = [o[name]];
            }
            o[name].push(this.value || '');
        } else {
            o[name] = [this.value || ''];
        }
    });
    return o;
};
