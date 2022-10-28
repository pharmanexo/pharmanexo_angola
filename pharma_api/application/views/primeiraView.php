<html>
    <head>
        <title>
            View de Teste
        </title>
    </head>
    <style>
        table, th, td {
            text-align: center;
            border:1px solid black;
        }
    </style>
    <body>
        <table style="width:100%">
            <thead>
                <tr>
                    <td>Data</td>
                    <td>Quantidade Cotação</td>
                    <td>Valor Total</td>
                    <td>Fornecedor</td>
                </tr>
            </thead>
            <tbody>
            <?php
                foreach($teste as $t){ ?>

                <tr>
                    <td><?php echo $t['data'] ?>  </td>
                    <td><?php echo $t['quantidadeCotacao'] ?>  </td>
                    <td><?php echo $t['valorTotal'] ?>  </td>
                    <td><?php echo $t['fornecedor'] ?>  </td>
                </tr>
                <?php } ?>

            </tbody>
        </table>
    </body>
</html>
