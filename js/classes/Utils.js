/**
 * 
 * @author Tobias
 * @version 03.01.2021
 * @since 31.12.2020
 */
class Utils{
  static delay = (duration = 0) => {
    if (duration === 0) return;
    const end = Date.now() + duration;
    while (Date.now() < end) {};
  }
}