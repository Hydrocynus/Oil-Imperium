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
}