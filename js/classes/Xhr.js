/**
 * 
 * @author Tobias
 * @version 03.01.2021
 * @since 31.12.2020
 */
class Xhr {
  /**
   * 
   * @author Tobias
   * @version 31.12.2020
   * @since 31.12.2020
   * @type {String}
   */
  static rootPath = ".";

  /**
   * 
   * @author Tobias
   * @version 31.12.2020
   * @since 31.12.2020
   * @type {String}
   */
  static gatewayUrl = "/php/Gateway.php";

  /**
   * 
   * @author Tobias
   * @version 31.12.2020
   * @since 31.12.2020
   * @type {String}
   */
  static getGatewayUrlFromRoot() {
    return this.rootPath + this.gatewayUrl;
  }

  /**
   * 
   * @author Tobias
   * @version 31.12.2020
   * @since 31.12.2020
   * @param {String} data 
   * @param {Boolean} [raw] 
   * @returns {Object|String} 
   */
  static async gateway(data, raw) {
    return await this.post(this.getGatewayUrlFromRoot(), data, raw);
  }

  /**
   * 
   * @author Tobias
   * @version 31.12.2020
   * @since 31.12.2020
   * @param {String} url 
   * @param {Boolean} [raw] 
   * @returns {Object|String} 
   */
  static async get(url, raw) {
    const resp = await this.xhr("GET", url);
    if (raw) return resp;
    else     return resp.responseText;
  }

  /**
   * 
   * @author Tobias
   * @version 31.12.2020
   * @since 31.12.2020
   * @param {String} url 
   * @param {String} data 
   * @param {Boolean} [raw] 
   * @returns {Object|String} 
   */
  static async post(url, data, raw) {
    const resp = await this.xhr("POST", url, data);
    if (raw) return resp;
    else     return resp.responseText;
  }

  /**
   * 
   * @author Tobias
   * @version 03.01.2021
   * @since 31.12.2020
   * @param {String} method 
   * @param {String} url 
   * @param {String} data 
   * @returns {Promise} 
   */
  static async xhr(method, url, data) {
    return new Promise((resolve, reject) => {
      const xhr = new XMLHttpRequest();

      xhr.onerror   = () => reject("error: " + xhr.readyState + ", " + xhr.statusText);
      xhr.ontimeout = () => reject("timeout");
      xhr.onload    = () => resolve(xhr);

      xhr.open(method, url, true);
      xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
      xhr.send(data);
    });
  }

  /**
   * 
   * @author Tobias
   * @version 03.01.2021
   * @since 03.01.2021
   * @param {Object} xhr 
   * @returns {Object} 
   */
  static parse(xhr) {
    if (xhr === undefined) {
      return undefined;
    }
    if (xhr.responseText === undefined) {
      try {
        return JSON.parse(xhr);
      } catch (e) {
        return xhr;
      }
    }
    try {
      return JSON.parse(xhr.responseText);
    } catch (e) {
      return xhr.responseText;
    }
  }
}