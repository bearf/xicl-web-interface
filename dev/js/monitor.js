function refreshMonitor(page, globalParams) {
    (function(params) {
        location.assign((function(URL, paramList) {
            return URL + paramList.join('&');
        })(
                page + '?'
            ,   (function(paramList) {
                    $.each(params, function(key, value) {
                        if (!key.length) { return; }
                        paramList.push([key, value].join('='));
                    });
                    return paramList;
                })([])
        ));
    })(
        (function(paramString) {
            return (function(params, paramList) {
                $.each(paramList, function(index, paramPair) {
                    paramPair = paramPair.split('=');
                    (function(key, value) {
                        params[key] = value;
                    })(
                            paramPair[0]
                        ,   paramPair.length > 0 ? paramPair[1] : ''
                    );
                });
                return $.extend(params, globalParams);
            })({}, paramString.split('&'));
        })(
            (function(paramString) {
                return paramString.indexOf('?') == 0 ? paramString.substr(1) : paramString;
            })(location.search)
        )
    );
}