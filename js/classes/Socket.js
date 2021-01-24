/**
 * 
 * @author Tobias
 * @version 09.01.2021
 * @since 29.12.2020
 */
class Socket {
  /**
   * Konstruktor der Klasse Socket.
   * @author Tim Tobias
   * @version 09.01.2021 (Tobias: onopen und onmessage Methoden)
   * @since 29.12.2020
   * @param {Number|String} host 
   * @param {Number|String} port 
   * @returns {void}
   */
  constructor(host, port) {
    this.host = host;
    this.port = port;
    this.socket;
    this.onopen, this.onmessage;
  }

  /**
   * Oeffnet das Socket.
   * @author Tobias Tim
   * @version 24.01.2021 (Tim: prueft ob Server bereits offen ist)
   * @since 09.01.2021
   * @returns {void}
   */
  open() {
    this.maxTryCount = 3;
    this.url = "ws://" + this.host + ":" + this.port;

    this.socket = new WebSocket(this.url);
    this.socket.onerror   = () => {
      this.openWebSocket(this.url);
    }

    this.socket.onopen    = this.onopen;
    this.socket.onmessage = this.onmessage;
  }

  /**
   * Versucht das WebSocket mit mehreren Versuchen zu starten.
   * @author Tobias Tim
   * @version 09.01.2021 (Tobias: onopen und onmessage Methoden)
   * @since 03.01.2021
   * @param {String} url URL des Websockets.
   * @returns {void}
   */
  async openWebSocket(url) {
    if (this.maxTryCount == 0) return;
    this.maxTryCount--;
    await this.startWebSocket();
    Utils.delay(1000);
    this.socket = new WebSocket(url);
    this.socket.onerror   = () => {
      this.openWebSocket(url);
    }
    this.socket.onopen    = this.onopen;
    this.socket.onmessage = this.onmessage;
  }

  /**
   * Sendet einen String ueber das Socket.
   * @author Tim Tobias
   * @version 03.01.2021
   * @since 08.12.2020
   * @param {String} msg Zu sendender String.
   * @returns {void}
   */
  send(msg) {
    this.socket.send(msg)
  }

  /**
   * Startet das Websocket Serverseitig.
   * @author Tobias
   * @version 03.01.2021
   * @since 29.12.2020
   * @param {Number|String} [host] URL des Sockets.
   * @param {Number|String} [port] Port des Sockets.
   * @returns {any} Rueckgabe der PHP Funktion.
   */
  async startWebSocket(host = this.host, port = this.port) {
    const data = "fnc=startWebSocket"
               + "&hst=" + encodeURIComponent(host)
               + "&prt=" + encodeURIComponent(port);
    const resp = await Xhr.gateway(data);
    return Xhr.parse(resp);
  }
}