

/**
 * Verwaltet Anweisung zum Server.
 * @author Jannis
 * @version 23.01.2021
 * @since 23.01.2021
 */
static class EventHandler {

  static cardAccept(value){
    gc.sendInstruction("cardYAccept");
  }

  static cardReject(value){
    gc.sendInstruction("cardReject");
  }

  static cardAction(value){
    gc.sendInstruction("cardAction");
  }

  static drillingStart(value){
    gc.sendInstruction("drillingStart");
  }
  
  static drillingAction(value){
    gc.sendInstruction("drillingAction");
  }

  static drillingFailed(value){
    gc.sendInstruction("drillingFailed");
  }

  static drillingSuccesses(value){
    gc.sendInstruction("drillingSuccesses");
  }

  static shipBuying(value){
    gc.sendInstruction("shipBuying");
  }
  
  static shipMovement(value){
    gc.sendInstruction("shipMovement");
  }

  static oelSelling(value){
    gc.sendInstruction("oelSelling");
  }

  static gameMoveEnd(value){
    gc.sendInstruction("gameMoveEnd");
  }

  static gameEnd(value){
    gc.sendInstruction("gameEnd");
  }
}
