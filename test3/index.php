<!DOCTYPE html>
<html>
<head>
    <!-- Standard Meta -->
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">

    <!-- Site Properties -->
    <title>Loan calculator</title>
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.2.10/semantic.css">

    <script src="https://code.jquery.com/jquery-1.12.4.js"
            integrity="sha256-Qw82+bXyGq6MydymqBxNPYTaUXXq7c8v3CwiYwLLNXU="
            crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.2.10/semantic.js"></script>

    <style type="text/css">
        body {
            background-color: #DADADA;
        }
        body > .grid {
            height: 100%;
        }
        .image {
            margin-top: -100px;
        }
        .column {
            max-width: 450px;
        }
    </style>
    <script>
        $(document).ready(function() {
            $('.ui.form').form({
                fields: {
                    loan_amount: ['empty', 'decimal'],
                    loan_period: ['empty', 'integer'],
                    interest_rate: ['empty', 'decimal']
                },
                onSuccess: function(e, fields) {
                    $('#loader').addClass('active');
                    $('#description').empty();
                    e.preventDefault();
                    $.ajax({
                        type: 'POST',
                        url: './calculate.php',
                        data: fields,
                        dataType: 'json',
                        success: function (data) {
                            var html = '<table class="ui selectable celled table">';
                            html += '<thead><tr>';
                            html += '<td>Payment number</td><td>Payment date</td><td>Principal debt</td>';
                            html += '<td>Interest</td><td>Total amount</td><td>Remaining debt</td>';
                            html += '</tr></thead><tbody>';
                            for (var i = 0; i < data.length; i++) {
                                html += '<tr>';
                                html += '<td>' + (i + 1) + '</td>';
                                html += '<td>' + data[i].date + '</td>';
                                html += '<td>' + data[i].principal_debt + '</td>';
                                html += '<td>' + data[i].interest + '</td>';
                                html += '<td>' + data[i].total_amount + '</td>';
                                html += '<td>' + data[i].remaining_debt + '</td>';
                                html += '</tr>';
                            }

                            html += '</tbody></table>';
                            $('#description').html(html);
                        },
                        complete: function () {
                            $('#loader').removeClass('active');
                            $('.ui.modal').modal('show');
                        },
                        error: function () {
                            $('#description').text('Request has been failed. Please check WEB server log.');
                        }
                    });
                }
            });
        });
    </script>
</head>
<body>

<div class="ui middle aligned center aligned grid">
    <div class="column">
        <h2 class="ui teal image header">
            <div class="content">
                Loan calculator
            </div>
        </h2>
        <form class="ui large form">
            <div id="loader" class="ui inverted dimmer">
                <div class="ui text loader">Loading</div>
            </div>
            <div class="ui raised segment">
                <div class="field">
                    <div class="ui left input">
                        <input type="number" name="loan_amount" required placeholder="Loan amount">
                    </div>
                </div>
                <div class="field">
                    <div class="ui left input">
                        <input type="number" name="loan_period" required placeholder="Loan period in months">
                    </div>
                </div>
                <div class="field">
                    <div class="ui left input">
                        <input type="number" name="interest_rate" required placeholder="Interest rate in %">
                    </div>
                </div>
                <div class="field">
                    <div class="ui left input">
                        <input type="date" name="first_payment" required placeholder="Date of first payment">
                    </div>
                </div>
                <div id="calculateBtn" class="ui fluid large teal submit button">Calculate</div>
            </div>

            <div class="ui error message"></div>

        </form>
    </div>
</div>

<div class="ui modal">
    <i class="close icon"></i>
    <div class="header">
        Payment Schedule
    </div>
    <div class="content">
        <div id="description" class="description">
        </div>
    </div>
    <div class="actions">
        <div class="ui positive right labeled icon button">
            OK
        </div>
    </div>
</div>

</body>

</html>
