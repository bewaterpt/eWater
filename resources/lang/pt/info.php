<?php
return [
    'title' => "Informação sobre: :subject",
    'view_report' => "<span class='lead'>
            Nesta página pode encontrar toda a informação acerca de um relatório especifico
        </span>
        <br>
        <br>
        No topo, se existirem comentários ou notas, estes são mostrados.
        <br>
        As informações de criação e atualização situam-se abaixo dos comentários.
        <br>
        Por baixo das informações de criação e atualização, é possível encontrar todos os itens do relatório agrupados por número de obra.
        <br>
        <br>
        Mais abaixo, no fundo da página é possível ver um registo de estados/ações executadas no relatório e por quem foram executadas. Os utilizadores que tenham acessos para movimentar um relatório podem faze-lo, recorrendo aos botões representados abaixo:
        <br>
        <ul>
            <li><i class='fas fa-step-backward text-danger'></i> Para retroceder um processo para o passo anterior</li>
            <li><i class='fas fa-step-forward text-success'></i> Para avançar um processo para o próximo passo</li>
            <li><i class='fas fa-ban text-danger'></i> Para cancelar o relatório</li>
        </ul>
        <br>
        Existem 3 outros botões que podem ser vistos nesta página, estão estes representados abaixo:
        <ul>
            <li><i class='ri-information-line ri-lg text-info'></i> Clicar para visualizar comentários sobre as ações executadas</li>
            <li><i class='ri-alert-line ri-lg text-danger'></i> Clicar para visualizar erros sobre as ações executadas</li>
            <li><i class='far fa-question-circle text-info'></i> Clicar para visualizar esta mensagem</li>
        </ul>
        ",
    'hours_as_quantity' => "<div>1 Hora = 1</div>
                            <div>30 minutos = 0.5</div>
                            <div>1h:30m = 1.5</div>",
    'report_km_difference' => "Existe uma incoerência entre os valores de quilómetragem deste relatório.",
    'report_has_errors' => "Existem erros neste relatório.",
];
