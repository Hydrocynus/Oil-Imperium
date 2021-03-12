# Was ist Babel?

Babel ist ein JavaScript Tool zum transpilieren von modernem JavaScript Code dient.

Mit Babel kann auf diese Weise eine Abwärtskompatibilität für ältere Browser (z.B. IE 9) geschaffen werden.

# Installation und Verwendung

1. Den Installationsordner in den Root-Ordner des aktuellen Projektes legen.
2. **install.cmd** ausführen und den Anweisungen folgen.
   * Nach dem Installieren kann die **Babel.config.json** angepasst werden:
     * Dort können die ältesten Browserversionen angegeben werden, die mindestens unterstützt werden sollen. *(IE 9 ist voreingestellt)*
     * Weitere Anpassungsmöglichkeiten stehen in der [Babel Dokumentation] (https://babeljs.io/docs/en/options)
3. **transpile.cmd** ausführen und dabei die Pfade für Input und Output angeben.
   * Der Input kann eine JavaScript Datei oder ein Ordner mit JavaScript Dateien sein.
   * Der Output ist eine einzige JavaScript Datei.
   * Die Pfade sind immer relativ von dem Ordner aus, der über dem Babel-Installationsordner liegt.

# Deinstallation

1. uninstall_1.cmd ausführen
2. Nachdem sich das Fenster geschlossen hat uninstall_2.cmd ausführen

> Node wird dabei nicht deinstalliert
