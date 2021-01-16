/**
 * 
 * @author Tobias
 * @version 03.01.2021
 * @since 31.12.2020
 */
class Xhr {
  /**
   * Relativer Pfad zur Root des Projektes.
   * @author Tobias
   * @version 31.12.2020
   * @since 31.12.2020
   * @type {String}
   */
  static rootPath = ".";

  /**
   * Relativer Pfad zur Gateway PHP ab Root.
   * @author Tobias
   * @version 31.12.2020
   * @since 31.12.2020
   * @type {String}
   */
  static gatewayUrl = "/php/Gateway.php";

  /**
   * Gibt die URL der Gateway PHP relativ von Root aus.
   * @author Tobias
   * @version 31.12.2020
   * @since 31.12.2020
   * @type {String}
   */
  static getGatewayUrlFromRoot() {
    return this.rootPath + this.gatewayUrl;
  }

  /**
   * Sendet einen POST an den PHP Gateway.
   * @author Tobias
   * @version 31.12.2020
   * @since 31.12.2020
   * @param {String} data Zu sendende Daten.
   * @param {Boolean} [raw] Wenn true, wird die Rueckgabe roh ausgegeben.
   * @returns {Object|String} Rueckgabe der Anfrage.
   */
  static async gateway(data, raw) {
    return await this.post(this.getGatewayUrlFromRoot(), data, raw);
  }

  /**
   * Sendet eine GET Anfrage.
   * @author Tobias
   * @version 31.12.2020
   * @since 31.12.2020
   * @param {String} url URL der Anfrage (inkl. Daten).
   * @param {Boolean} [raw] Wenn true, wird die Rueckgabe roh zurueckgegeben.
   * @returns {Object|String} Rueckgabe der Anfrage.
   */
  static async get(url, raw) {
    const resp = await this.xhr("GET", url);
    if (raw) return resp;
    else     return resp.responseText;
  }

  /**
   * Sendet eine POST Anfrage.
   * @author Tobias
   * @version 31.12.2020
   * @since 31.12.2020
   * @param {String} url URL der Anfrage.
   * @param {String} data Daten der Anfrage.
   * @param {Boolean} [raw] Wenn true, wird das XHR Objekt selbst ausgegeben.
   * @returns {Object|String} Rueckgabestring oder XHR Objekt.
   */
  static async post(url, data, raw) {
    const resp = await this.xhr("POST", url, data);
    if (raw) return resp;
    else     return resp.responseText;
  }

  /**
   * Sendet eine XHR Anfrage.
   * @author Tobias
   * @version 03.01.2021
   * @since 31.12.2020
   * @param {String} method Methode der Anfrage (GET, POST, ...).
   * @param {String} url URL der Anfrage.
   * @param {String} data Daten der Anfrage (Z.B. bei POST).
   * @returns {Promise} Gibt das Object der Anfrage zurueck (XMLHttpRequest).
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
   * Wandelt eine XHR Rueckgabe in ein Javascript Objekt um.
   * @author Tobias
   * @version 03.01.2021
   * @since 03.01.2021
   * @param {Object} xhr Rueckgabe der Anfrage.
   * @returns {Object} Umgewandeltes Objekt.
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