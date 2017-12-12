var ajax = {
	getXmlHttp: function (){
		try{return new XMLHttpRequest();} catch (e){
		try{return new ActiveXObject('Microsoft.XMLHTTP');} catch (e){
		try{return new ActiveXObject('Msxml2.XMLHTTP');} catch (e){ return false;}}}
	},
	makeQuery: function (data) {
		switch (typeof data) {
			case 'string':
				return data;
			case 'object':
				var query = '';
				var i = 0;
				for (index in data) {
					if (i > 0) 
						query += '&';
					query += encodeURIComponent(index) + '=' + encodeURIComponent(data[index]);
					i++;
				}
				return query;
			default:
				return 0;
		}
	},
	send: function (method, url, data, callback = this.responseHandler){
		method = method.toUpperCase();
		var req = this.getXmlHttp();
		if (req === false) return false;
		req.onreadystatechange = function (){
			if (req.readyState == 4){ callback(req.responseText); }
		}
		var query = this.makeQuery(data);
		if (query && method == 'GET') url += '?' + query;
		req.open(method, url, true);
		req.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
		req.send((method == 'POST') ? query : null);
		this.loadingHandler();
	},
	post: function (url, data, callback = this.responseHandler){
		return this.send('post', url, data, callback);
	},
	get: function (url, data, callback = this.responseHandler){
		return this.send('get', url, data, callback);
	},
	loadingHandler: function (){
		console.log('loading...');
	},
	responseHandler: function (text){
		console.log(text);
	}
}