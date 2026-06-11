# Simulation: Interferenz zweier Wellen (2D‑Darstellung)

## Ziel
Erstelle eine **interaktive Simulation** im `<canvas>`‑Element, die das physikalische Konzept der **Interferenz zweier Wellen** visualisiert.  
Die neue Simulation soll **im selben Stil, Layout und ästhetischen Design** wie die bestehende Simulation in `Schwingungen_Startseite.html` umgesetzt werden.

Für diese Simulation sollen **neue Dateien** angelegt werden:
- `Interferenz.html`
- `interferenz.css`
- `interferenz.js`

**Wichtig:**  
- Die **Navigation**, der **Seitenaufbau**, der **Textbereich** und alle **nicht‑simulationsbezogenen Elemente** sollen **unverändert** bleiben.  
- Nur der Bereich der Simulation (Canvas + Slider) wird ersetzt.

---

## Technische Anforderungen

### 1. Canvas‑Inhalt
- Darstellung von **zwei einzelnen Wellenpaketen** (keine kontinuierlichen Sinuskurven).
- **Welle A** startet links und bewegt sich nach rechts.  
- **Welle B** startet rechts und bewegt sich nach links.
- Beide Wellen:
  - bewegen sich **gleich schnell** aufeinander zu,
  - bestehen aus **einer einzelnen Hebung** (ein einzelner „Pulse“),
  - sollen sich beim Zusammentreffen **konstruktiv oder destruktiv interferieren**.
- Nach dem Durchlaufen soll der Ablauf **periodisch wiederholt** werden.
- Die **Geschwindigkeit** bestimmt:
  - die Bewegungsgeschwindigkeit der Pulse,
  - den zeitlichen Abstand bis zur Wiederholung.
- Farben:
  - Welle A: `#4285F4` (Blau)
  - Welle B: `#EA4335` (Rot)
  - Resultierende Interferenz: neutrale Farbe (z. B. Schwarz oder Dunkelgrau)
- Linienbreite: `2px`
- Koordinatensystem, Hintergrund, Achsen und generelle Ästhetik sollen **identisch** zur bestehenden Simulation sein.

---

### 2. Schieberegler (Slider)

#### Platzierung
- **Links**: Zwei Slider für **Welle A** (Amplitude, Wellenlänge)  
- **Rechts**: Zwei Slider für **Welle B** (Amplitude, Wellenlänge)  
- **Unterhalb über die gesamte Breite**: Ein Slider für die **Geschwindigkeit**

#### Slider‑Funktionalität
- **Amplitude Welle A**
  - Bereich: `-200px` bis `200px`
  - Standard: `100px`
  - Schrittweite: `5px`
  - Label: `"Amplitude A: [Wert] px"`

- **Wellenlänge Welle A**
  - Bereich: `50px` bis `500px`
  - Standard: `200px`
  - Schrittweite: `10px`
  - Label: `"Wellenlänge A: [Wert] px"`

- **Amplitude Welle B**
  - Bereich: `-200px` bis `200px`
  - Standard: `100px`
  - Schrittweite: `5px`
  - Label: `"Amplitude B: [Wert] px"`

- **Wellenlänge Welle B**
  - Bereich: `50px` bis `500px`
  - Standard: `200px`
  - Schrittweite: `10px`
  - Label: `"Wellenlänge B: [Wert] px"`

- **Geschwindigkeit**
  - Bereich: `0.1` bis `5`
  - Standard: `1`
  - Schrittweite: `0.1`
  - Label: `"Geschwindigkeit: [Wert]"`

#### Stil
- Slider sollen die bestehende CSS‑Klasse `slider` aus der anderen Simulation verwenden.
- Die beiden linken Slider sollen **vertikal übereinander** stehen.
- Die beiden rechten Slider ebenfalls.
- Der Geschwindigkeits‑Slider soll **zentriert** über die gesamte Breite gehen.

---

### 3. Interferenz‑Logik
- Die beiden Pulse sollen sich beim Zusammentreffen **überlagern**:
  - **Konstruktive Interferenz** bei gleicher Vorzeichenrichtung.
  - **Destruktive Interferenz** bei entgegengesetzter Amplitude.
- Die resultierende Welle soll **sichtbar** dargestellt werden.
- Die Animation soll **flüssig** laufen (`requestAnimationFrame`).

---

### 4. Dynamische Anpassung
- Änderungen der Slider sollen:
  - die Form der Pulse,
  - die Interferenz,
  - die Geschwindigkeit,
  - den Wiederholungszeitpunkt  
  **in Echtzeit** beeinflussen.
- Keine Ruckler oder Verzögerungen.

---

### 5. CSS‑Integration
- Nutze die bestehende `test.css` als Grundlage.
- Falls nötig, minimal zusätzliche Regeln in `interferenz.css` ergänzen.
- Ästhetik, Farben, Schriftarten und Layout sollen **identisch** zur bestehenden Simulation bleiben.

---

### 6. Code‑Struktur (Beispiel)
```html
<!-- HTML (in Interferenz.html) -->
<canvas id="interferenzCanvas"></canvas>

<div class="slider-row">
  <div class="slider-column">
    <label>Amplitude A: <span id="ampAValue">100</span> px</label>
    <input type="range" id="ampASlider" min="-200" max="200" step="5" value="100">

    <label>Wellenlänge A: <span id="lambdaAValue">200</span> px</label>
    <input type="range" id="lambdaASlider" min="50" max="500" step="10" value="200">
  </div>

  <div class="slider-column">
    <label>Amplitude B: <span id="ampBValue">100</span> px</label>
    <input type="range" id="ampBSlider" min="-200" max="200" step="5" value="100">

    <label>Wellenlänge B: <span id="lambdaBValue">200</span> px</label>
    <input type="range" id="lambdaBSlider" min="50" max="500" step="10" value="200">
  </div>
</div>

<div class="slider-full">
  <label>Geschwindigkeit: <span id="speedValue">1</span></label>
  <input type="range" id="speedSlider" min="0.1" max="5" step="0.1" value="1">
</div>
```
