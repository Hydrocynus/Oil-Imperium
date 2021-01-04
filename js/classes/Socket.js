/**
 * Klasse für WebSocket Verbindungen
 * @author Tobias
 * @version 03.01.2021
 * @since 29.12.2020
 */
class Socket {
  /**
   * Erstellt neue WebSocket Verbindung.
   * @author Tim Tobias
   * @version 03.01.2021
   * @since 29.12.2020
   * @param {Number|String} host Adresse des WebSockets.
   * @param {Number|String} port Port des Websockets.
   * @returns {void}
   */
  constructor(host, port) {
    this.host = host;
    this.port = port;
    this.maxTryCount = 3;
    let url = "ws://" + host + ":" + port;
    this.openWebSocket(url);
  }

  /**
   * Oeffnet eine neue WebSocket Verbindung.
   * Versucht den WebSocket Server zu starten, wenn keine Verbindung moeglich ist.
   * @author Tobias
   * @version 04.01.2021 (Tim) onmessage hinzugefügt
   * @since 03.01.2021
   * @param {String} url URL des WebSockets.
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
    this.socket.onopen  = (e) => console.debug("WebSocket connected on " + url);
    this.socket.onmessage = (e) => { console.debug("WebSocket message "+ e.data);}
  }

  /**
   * Sendet eine Nachricht ueber den WebSocket.
   * @author Tim Tobias
   * @version 03.01.2021
   * @since 08.12.2020
   * @param {String} msg Nachricht.
   * @returns {void}
   */
  send(msg) {
    this.socket.send(msg);
  }

  /**
   * Startet einen WebSocket Server.
   * @author Tobias
   * @version 03.01.2021
   * @since 29.12.2020
   * @param {Number|String} [host] Adresse des Servers. Standardmaessig wird das Objektattribut 'host' verwendet.
   * @param {Number|String} [port] Port des Servers. Standardmaessig wird das Objektattribut 'port' verwendet.
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