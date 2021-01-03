/**
 * 
 * @author Tobias
 * @version 31.12.2020
 * @since 31.12.2020
 */
Utils = function(){
  /**
   * 
   * @author Tobias
   * @version 31.12.2020
   * @since 31.12.2020
   * @param {Number} duration 
   */
  const delay = (duration = 0) => {
    if (duration === 0) return;
    const end = Date.now() + duration;
    while (Date.now() < end) {};
  }

  return {
    delay: delay
  }
}();