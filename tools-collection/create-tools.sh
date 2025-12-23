#!/bin/bash
# Script to create tools directory on server

ssh -p 65002 u520004865@77.37.90.129 << 'DEPLOY'
cd domains/adswebstrom.com/public_html

# Create directories
mkdir -p tools/calculator
mkdir -p tools/converter

# Create calculator
cat > tools/calculator/index.html << 'CALC'
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Calculator Tool</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="calculator">
        <h1>Calculator</h1>
        <input type="text" id="display" class="display" readonly value="0">
        <div class="buttons">
            <button onclick="appendToDisplay('7')">7</button>
            <button onclick="appendToDisplay('8')">8</button>
            <button onclick="appendToDisplay('9')">9</button>
            <button onclick="appendToDisplay('/')">÷</button>
            <button onclick="appendToDisplay('4')">4</button>
            <button onclick="appendToDisplay('5')">5</button>
            <button onclick="appendToDisplay('6')">6</button>
            <button onclick="appendToDisplay('*')">×</button>
            <button onclick="appendToDisplay('1')">1</button>
            <button onclick="appendToDisplay('2')">2</button>
            <button onclick="appendToDisplay('3')">3</button>
            <button onclick="appendToDisplay('-')">-</button>
            <button onclick="appendToDisplay('0')">0</button>
            <button onclick="appendToDisplay('.')">.</button>
            <button onclick="calculate()">=</button>
            <button onclick="clearDisplay()">C</button>
        </div>
    </div>
    <script src="script.js"></script>
</body>
</html>
CALC

# Create calculator styles
cat > tools/calculator/style.css << 'STYLE'
* { margin: 0; padding: 0; box-sizing: border-box; }
body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 100vh;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}
.calculator {
    background: white;
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 10px 40px rgba(0,0,0,0.3);
    max-width: 300px;
}
.calculator h1 { text-align: center; margin-bottom: 20px; font-size: 24px; }
.display {
    width: 100%;
    padding: 15px;
    font-size: 24px;
    margin-bottom: 15px;
    border: 2px solid #ddd;
    border-radius: 5px;
    text-align: right;
    font-weight: bold;
}
.buttons {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 10px;
}
button {
    padding: 15px;
    font-size: 16px;
    border: none;
    border-radius: 5px;
    background: #667eea;
    color: white;
    cursor: pointer;
    font-weight: bold;
    transition: all 0.3s;
}
button:hover { background: #764ba2; transform: scale(1.05); }
button:active { transform: scale(0.95); }
STYLE

# Create calculator script
cat > tools/calculator/script.js << 'SCRIPT'
let display = document.getElementById('display');
let currentValue = '';

function appendToDisplay(value) {
    if (display.value === '0') display.value = '';
    display.value += value;
    currentValue = display.value;
}

function calculate() {
    try {
        display.value = eval(display.value);
        currentValue = display.value;
    } catch (e) {
        display.value = 'Error';
    }
}

function clearDisplay() {
    display.value = '0';
    currentValue = '';
}
SCRIPT

# Create converter
cat > tools/converter/index.html << 'CONV'
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Unit Converter</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .converter {
            max-width: 400px;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.3);
        }
        .converter h1 { text-align: center; margin-bottom: 30px; }
        .conversion-group {
            margin-bottom: 25px;
        }
        .conversion-group label { display: block; margin-bottom: 8px; font-weight: bold; }
        input {
            width: 100%;
            padding: 12px;
            font-size: 16px;
            border: 2px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
        }
        input:focus { border-color: #667eea; outline: none; }
    </style>
</head>
<body>
    <div class="converter">
        <h1>Unit Converter</h1>
        
        <div class="conversion-group">
            <label>Kilometers</label>
            <input type="number" id="km" placeholder="Enter kilometers" oninput="convertKM()">
        </div>
        
        <div class="conversion-group">
            <label>Miles</label>
            <input type="number" id="miles" placeholder="Enter miles" oninput="convertMiles()">
        </div>
        
        <div class="conversion-group">
            <label>Kilograms</label>
            <input type="number" id="kg" placeholder="Enter kilograms" oninput="convertKG()">
        </div>
        
        <div class="conversion-group">
            <label>Pounds</label>
            <input type="number" id="lbs" placeholder="Enter pounds" oninput="convertLBS()">
        </div>
    </div>
    
    <script>
        function convertKM() {
            const km = parseFloat(document.getElementById('km').value) || 0;
            document.getElementById('miles').value = (km * 0.621371).toFixed(2);
        }
        function convertMiles() {
            const miles = parseFloat(document.getElementById('miles').value) || 0;
            document.getElementById('km').value = (miles / 0.621371).toFixed(2);
        }
        function convertKG() {
            const kg = parseFloat(document.getElementById('kg').value) || 0;
            document.getElementById('lbs').value = (kg * 2.20462).toFixed(2);
        }
        function convertLBS() {
            const lbs = parseFloat(document.getElementById('lbs').value) || 0;
            document.getElementById('kg').value = (lbs / 2.20462).toFixed(2);
        }
    </script>
</body>
</html>
CONV

echo ""
echo "✓ Tools created successfully"
echo ""
echo "=== VERIFYING ==="
ls -la tools/
echo ""
echo "=== TESTING URLS ==="
sleep 2
curl -s -I https://adswebstrom.com/tools/calculator/ | head -5
echo ""
curl -s -I https://adswebstrom.com/tools/converter/ | head -5
DEPLOY
