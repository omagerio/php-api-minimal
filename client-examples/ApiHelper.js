class ApiHelper {
    constructor() {
        this.url = "../api/api.php";
    }

    _call(method, parameters) {
        return new Promise(
            (resolve) => {
                let stringParameters = JSON.stringify(parameters);
                let url = this.url;

                if (method == "get") {
                    url += "?" + encodeURIComponent(stringParameters);
                }

                let xhr = new XMLHttpRequest();
                xhr.open(method, url);
                xhr.setRequestHeader("Content-type", "application/json;charset=utf8");
                xhr.onreadystatechange = () => {
                    if (xhr.readyState == 4) {
                        resolve(xhr.response);
                    }
                };

                if (method == "post") {
                    xhr.send(stringParameters);
                } else {
                    xhr.send();
                }
            }
        );

    }

    async get(parameters) {
        return await this._call("get", parameters);
    }

    async post(parameters) {
        return await this._call("post", parameters);
    }
}