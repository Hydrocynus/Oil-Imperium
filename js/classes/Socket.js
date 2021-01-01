/**
 * 
 * @author Tobias
 * @version 31.12.2020
 * @since 29.12.2020
 */
class Socket {
  /**
   * 
   * @author Tobias
   * @version 29.12.2020
   * @since 29.12.2020
   * @type {Number}
   */
  static maxTryCount = 3;

  /**
   * 
   * @author Tim Tobias
   * @version 29.12.2020
   * @since 29.12.2020
   * @type {Number|String}
   */
  host;

  /**
   * 
   * @author Tim Tobias
   * @version 29.12.2020
   * @since 29.12.2020
   * @type {Number|String}
   */
  port;

  /**
   * 
   * @author Tim Tobias
   * @version 29.12.2020
   * @since 29.12.2020
   * @param {Number|String} host 
   * @param {Number|String} port 
   * @returns 
   */
  constructor(host, port) {
    let url = "ws://" + host + ":" + port;

    for (let i=this.maxTryCount; i>0; i--) {
      this.socket = new WebSocket(url);
      if (this.isOpen()) {
        break;
      }
      else {

      }
    }
  }

  /**
   * 
   * @author Tobias
   * @version 29.12.2020
   * @since 29.12.2020
   * @param {Boolean} waitIfConnecting 
   * @returns 
   */
  isOpen(waitIfConnecting = true) {
    if (waitIfConnecting) {
      while (this.socket.readyState === WebSocket.CONNECTING) {};
    }
    return this.socket.readyState === WebSocket.OPEN;
  }

  /**
   * 
   * @author Tobias
   * @version 31.12.2020
   * @since 29.12.2020
   * @param {Number|String} [host] 
   * @param {Number|String} [port] 
   * @returns {void}
   */
  static async startWebSocket(host = this.host, port = this.port) {
    const data = "fnc=startWebSocket"
               + "&hst=" + encodeURIComponent(host)
               + "&prt=" + encodeURIComponent(port);
    Xhr.gateway(data);
  }
}