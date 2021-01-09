/**
 * 
 * @author Tobias
 * @version 09.01.2021
 * @since 09.01.2021
 */
class GameControl {

  /**
   * 
   * @author Tobias
   * @version 09.01.2021
   * @since 09.01.2021
   */
  constructor(socket) {
    if (socket === undefined) socket = new Socket(localStorage.getItem("ip"), localStorage.getItem("port"));
    socket.onerror   = () => console.debug("Websocket konnte nicht verbunden werden!");
    socket.onopen    = e  => console.debug("WebSocket geöffnet", e);
    socket.onmessage = e  => this.messageHandler(e.data);
    socket.open();
  }

  /**
   * 
   * @author Tobias
   * @version 09.01.2021
   * @since 09.01.2021
   */
  messageHandler(message) {
    console.debug("Neue Nachricht:", message);
  }

}