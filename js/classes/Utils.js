/**
 * Klasse fuer nuetzliche Funktionen.
 * @author Tobias
 * @version 03.01.2021
 * @since 31.12.2020
 */
class Utils{
  /**
   * Verzoegert das Script um eine angegebene Zeit.
   * Hochgradig ungenau.
   * @author Tobias
   * @version 03.01.2021
   * @since 03.01.2021
   * @param {Number} duration Dauer der Verzoegerung in Millisekunden.
   * @returns {void}
   */
  static delay = (duration = 0) => {
    if (duration === 0) return;
    const end = Date.now() + duration;
    while (Date.now() < end) {};
  }

  /**
   * Gibt automatisch zu einem RGB Wert an, ob die Schriftfarbe weiss oder schwarz sein sollte.
   * @author T.U.
   * @version 23.01.2021
   * @since 23.01.2021
   * @param {number} r Rot (0-255).
   * @param {number} g Gruen (0-255).
   * @param {number} b Blau (0-255).
   * @returns {number} 255 (weiss) oder 0 (schwarz).
   */
  static fontColorAutoKontrast(r,g,b) {
    let avg = r*0.35 + g*0.4 + b*0.25;
    return avg < 128 ? 255 : 0;
  }

  /**
   * Gibt automatisch zu einem Hex-Farbcode an, ob die Schriftfarbe weiss oder schwarz sein sollte.
   * @author Tobias
   * @version 23.01.2021
   * @since 23.01.2021
   * @static
   * @param {string} hex Hex-Farbcode der Hintergrundfarbe.
   * @returns {string} Hex-Farbcode der Schriftfarbe.
   * @memberof Utils
   */
  static fontColorAutoKontrastHex(hex) {
    let color = this.hexToRGB(hex);
    color = this.fontColorAutoKontrast(color.r, color.g, color.b);
    return this.rgbToHex(color, color, color);
  }

  /**
   * Wandelt einen Hex-Farbcode zu einem RGB Wert um.
   * @author Tobias
   * @copyright Stack Overflow https://stackoverflow.com/users/96100/tim-down - https://stackoverflow.com/a/5624139
   * @version 23.01.2021
   * @since 23.01.2021
   * @param {string} hex Hex-Farbcode.
   * @returns {object} RGB Werte. Adressierbar durch r, g und b.
   * @static
   * @memberof Utils
   */
  static hexToRGB(hex) {
    const rgb = /^#?([a-f\d]{2})([a-f\d]{2})([a-f\d]{2})$/i.exec(hex);
    return rgb ? {
      r: parseInt(rgb[1], 16),
      g: parseInt(rgb[2], 16),
      b: parseInt(rgb[3], 16)
    } : null;
  }

  /**
   * Wandelt einen RGB Wert zu einem Hex-Farbcode um.
   * @author Tobias
   * @copyright Stack Overflow https://stackoverflow.com/users/96100/tim-down - https://stackoverflow.com/a/5624139
   * @version 23.01.2021
   * @since 23.01.2021
   * @static
   * @param {number} r Rot (0-255).
   * @param {number} g Gruen (0-255).
   * @param {number} b Blau (0-255).
   * @returns {string} Hex-Farbcode 
   * @memberof Utils
   */
  static rgbToHex(r, g, b) {
    return "#" + ((1 << 24) + (r << 16) + (g << 8) + b).toString(16).slice(1);
  }
}