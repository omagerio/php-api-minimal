class ApiHelper {
    constructor() {
        this.url = "../api/api.php";
    }

    /**
     * Makes a GET request to the server. Parameters are placed in the query string.
     * @param {object} parameters
     * @returns Raw server response
     */
    async get(parameters) {
        return await this._call("get", parameters);
    }

    /**
     * Makes a POST request to the server. Parameters are placed in the request body.
     * @param {object} parameters
     * @returns Raw server response
     */
    async post(parameters) {
        return await this._call("post", parameters);
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
}