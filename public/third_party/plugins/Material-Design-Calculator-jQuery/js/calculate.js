/* eslint-disable no-eval */
console.log('no-eval')
/* eslint-disable no-eval */

$(document).ready(function () {
    var displayBox = document.getElementById('display')
    var hasEvaluated = false

    // CHECK IF 0 IS PRESENT. IF IT IS, OVERRIDE IT, ELSE APPEND VALUE TO DISPLAY
    function clickNumbers(val) {
        if (displayBox.value === '0' || (hasEvaluated === true && !isNaN(displayBox.value))) {
            displayBox.value = val;
        } else {
            displayBox.value += val
        }
        hasEvaluated = false
    }

    displayBox.click(function () {
        displayBox.value = '';
    });

    $('#display').on('keypress', function (e) {
        if (e.which == 13) $('#equals').click();
    });

    // PLUS MINUS
    $('#plus_minus').click(function () {
        if (eval(displayBox.value) > 0) {
            displayBox.value = '-' + displayBox.value
        } else {
            displayBox.value = displayBox.value.replace('-', '')
        }
    })

    // ON CLICK ON NUMBERS
    $('#clear').click(function () {
        displayBox.value = '0';
        $('#display').css('margin-top', '110px');
        $('button').prop('disabled', false);
    })
    $('#one').click(function () {
        checkLength(displayBox.value)
        clickNumbers(1)
    })
    $('#two').click(function () {
        checkLength(displayBox.value)
        clickNumbers(2)
    })
    $('#three').click(function () {
        checkLength(displayBox.value)
        clickNumbers(3)
    })
    $('#four').click(function () {
        checkLength(displayBox.value)
        clickNumbers(4)
    })
    $('#five').click(function () {
        checkLength(displayBox.value)
        clickNumbers(5)
    })
    $('#six').click(function () {
        checkLength(displayBox.value)
        clickNumbers(6)
    })
    $('#seven').click(function () {
        checkLength(displayBox.value)
        clickNumbers(7)
    })
    $('#eight').click(function () {
        checkLength(displayBox.value)
        clickNumbers(8)
    })
    $('#nine').click(function () {
        checkLength(displayBox.value)
        clickNumbers(9)
    })
    $('#zero').click(function () {
        checkLength(displayBox.value)
        clickNumbers(0)
    })
    $('#decimal').click(function () {
        if (displayBox.value.indexOf('.') === -1 ||
            (displayBox.value.indexOf('.') !== -1 && displayBox.value.indexOf('+') !== -1) ||
            (displayBox.value.indexOf('.') !== -1 && displayBox.value.indexOf('-') !== -1) ||
            (displayBox.value.indexOf('.') !== -1 && displayBox.value.indexOf('×') !== -1) ||
            (displayBox.value.indexOf('.') !== -1 && displayBox.value.indexOf('÷') !== -1)) {
            clickNumbers('.')
        }
    })

    // OPERATORS
    $('#add').click(function () {
        evaluate()
        checkLength(displayBox.value)
        displayBox.value += '+'
    })
    $('#subtract').click(function () {
        evaluate()
        checkLength(displayBox.value)
        displayBox.value += '-'
    })
    $('#multiply').click(function () {
        evaluate()
        checkLength(displayBox.value)
        displayBox.value += '×'
    })
    $('#divide').click(function () {
        evaluate()
        checkLength(displayBox.value)
        displayBox.value += '÷'
    })
    $('#square').click(function () {
        var num = Number(displayBox.value)
        num = num * num
        checkLength(num)
        displayBox.value = num
    })
    $('#sqrt').click(function () {
        var num = parseFloat(displayBox.value)
        num = Math.sqrt(num)
        displayBox.value = Number(num.toFixed(5))
    })
    $('#equals').click(function () {
        evaluate()
        hasEvaluated = true
    })

    // EVAL FUNCTION
    function evaluate() {
        // displayBox.value = displayBox.value.replace('.', '')
        displayBox.value = displayBox.value.split(",").join(".");
        displayBox.value = displayBox.value.replace('×', '*');
        displayBox.value = displayBox.value.replace('÷', '/');
        if (displayBox.value.indexOf('/0') !== -1) {
            $('#display').css('margin-top', '124px')
            $('button').prop('disabled', false)
            $('.clear').attr('disabled', false)
            displayBox.value = 'Erro! Divisão por zero.'
        }
        console.log(displayBox.value);
        var evaluate = eval(displayBox.value);
        checkLength(evaluate);
        displayBox.value = evaluate;
    }

    // CHECK FOR LENGTH & DISABLING BUTTONS
    function checkLength(num) {
        if (num.toString().length > 7 && num.toString().length < 14) {
            
            $('#display').css('margin-top', '174px')
        } else if (num.toString().length > 16) {
            num = 'Infinity'
            $('button').prop('disabled', true)
            $('.clear').attr('disabled', false)
        }
    }

    // TRIM IF NECESSARY
    function trimIfNecessary() {
        file = 'standard ignore' // eslint-disable-line
        var length = displayBox.value.length
        if (length > 7 && length < 14) {
            $('#display').css('margin-top', '174px')
        } else if (length > 14) {
            displayBox.value = 'Infinity'
            $('button').prop('disabled', true)
            $('.clear').attr('disabled', false)
        }
    }

})
