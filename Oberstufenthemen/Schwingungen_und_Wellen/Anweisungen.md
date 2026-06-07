# Simulation: Grundlagen einer Schwingung (2D Sinuskurve)

## Ziel
Erstelle eine **interaktive Simulation** im `<canvas>`-Element der `Schwingungen_Startseite.html`.
Die Simulation soll die Grundlagen einer Schwingung veranschaulichen:
- **2D-Graph** mit einer Sinuskurve, die sich von **links nach rechts** bewegt.
- **Schieberegler** (Slider) zur Steuerung von:
  - **Frequenz** (Geschwindigkeit der Bewegung)
  - **Amplitude** (Höhe der Welle)

---

## Technische Anforderungen

### 1. Canvas-Inhalt
- **Sinuskurve**:
  - Startet bei `(0, 0)`.
  - Bewegt sich **kontinuierlich von links nach rechts** (Animation).
  - Farbe: `#4285F4` (Blau) oder ähnlich.
  - Linie: `stroke-width: 2px`.

### 2. Beschriftungen
- **Amplitude**:
  - **Position**: Rechts neben dem Graphen.
  - **Darstellung**:
    - Text: `"Amplitude: [Wert]"` (dynamisch aktualisiert).
    - **Maßklammer**: Vertikale Linie mit horizontalen Strichen an Start- und Endpunkt der Amplitude.
    - Farbe: `#EA4335` (Rot).
- **Wellenlänge**:
  - **Position**: Über der Sinuskurve (zentriert auf eine Wellenlänge).
  - **Darstellung**:
    - Text: `"Wellenlänge: [Wert]"` (dynamisch aktualisiert).
    - **Maßklammer**: Horizontale Linie mit vertikalen Strichen an Start- und Endpunkt einer Wellenlänge.
    - Farbe: `#34A853` (Grün).

### 3. Schieberegler (Slider)
- **Platzierung**: Unter dem `<canvas>`-Element.
- **Stil**: CSS-Klasse `slider` (aus `test.css` verwenden oder anpassen).
- **Funktionalität**:
  - **Frequenz**:
    - Bereich: `0.1 Hz` bis `5 Hz` (Standard: `1 Hz`).
    - Schrittweite: `0.1 Hz`.
    - Label: `"Frequenz: [Wert] Hz"`.
  - **Amplitude**:
    - Bereich: `10px` bis `200px` (Standard: `100px`).
    - Schrittweite: `5px`.
    - Label: `"Amplitude: [Wert] px"`.

### 4. Dynamische Anpassung
- **Maßklammern** und **Beschriftungen** müssen sich **in Echtzeit** an die Schieberegler-Werte anpassen.
- **Animation**:
  - Flüssige Bewegung der Sinuskurve (z. B. `requestAnimationFrame`).
  - Keine Ruckler oder Verzögerungen.

### 5. CSS-Integration
- Nutze die bestehende `test.css` für Stile (z. B. für Slider, Text, Linien).
- Falls nötig, füge **minimale zusätzliche CSS-Regeln** hinzu (z. B. für die Maßklammern).

---

## Code-Struktur (Beispiel)
```html
<!-- HTML (in Schwingungen_Startseite.html) -->
<canvas id="schwingungCanvas"></canvas>
<div class="slider-container">
  <label>Frequenz: <span id="freqValue">1</span> Hz</label>
  <input type="range" id="freqSlider" min="0.1" max="5" step="0.1" value="1">

  <label>Amplitude: <span id="ampValue">100</span> px</label>
  <input type="range" id="ampSlider" min="10" max="200" step="5" value="100">
</div>