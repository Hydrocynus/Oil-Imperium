/**
 * 
 * @author Tobias
 * @version 03.01.2021
 * @since 29.12.2020
 */
class Socket {
  /**
   * 
   * @author Tim Tobias
   * @version 03.01.2021
   * @since 29.12.2020
   * @param {Number|String} host 
   * @param {Number|String} port 
   * @returns 
   */
  constructor(host, port) {
    this.host = host;
    this.port = port;
    this.onopen, this.onmessage;

    this.maxTryCount = 3;
    let url = "ws://" + host + ":" + port;
    this.openWebSocket(url);
  }

  /**
   * 
   * @author Tobias
   * @version 04.01.2021 (Tim) onmessage hinzugefügt
   * @since 03.01.2021
   * @param {String} url 
   * @returns {void}
   */
  openWebSocket(url) {
    if (this.maxTryCount == 0) return;
    this.maxTryCount--;
    this.socket = new WebSocket(url);
    this.socket.onerror = () => {
      this.startWebSocket();
      Utils.delay(1000);
      this.openWebSocket(url);
    }
    this.socket.onopen    = this.onopen;
    this.socket.onmessage = this.onmessage;
  }

  /**
   * 
   * @author Tim Tobias
   * @version 03.01.2021
   * @since 08.12.2020
   * @param {String} msg 
   * @returns {void}
   */
  send(msg) {
    this.socket.send(msg);
  }

  /**
   * 
   * @author Tobias
   * @version 03.01.2021
   * @since 29.12.2020
   * @param {Number|String} [host] 
   * @param {Number|String} [port] 
   * @returns {void}
   */
  async startWebSocket(host = this.host, port = this.port) {
    const data = "fnc=startWebSocket"
               + "&hst=" + encodeURIComponent(host)
               + "&prt=" + encodeURIComponent(port);
    const resp = await Xhr.gateway(data);
    return Xhr.parse(resp);
  }
}