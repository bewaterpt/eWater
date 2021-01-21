<?php
    return [
        'go_to_model' => 'Ver Interrupção',
        'fallback_link' => 'Se o botão acima não está a funcionar, clique aqui: <a href=":url">:url</a>',
        'interruptions' => [
            'warning' => 'Aviso',
            'scheduled' => [
                'created_subject' => "Interrupção programada com as seguintes características (:id)",
                'created' => "Interrupção <b>programada</b> com as seguintes características",
                'updated_subject' => "Alteração de interrupção programada (:id)",
                'updated' => "Alteração de interrupção <b>programada</b>",
                'canceled_subject' => "Cancelamento de interrupção programada (:id)",
                'canceled' => "Cancelamento de interrupção <b>programada</b>",
            ],
            'unscheduled' => [
                'created_subject' => "Interrupção não programada com as seguintes características (:id)",
                'created' => "Interrupção <b>não programada</b> com as seguintes características",
                'updated_subject' => "Alteração de interrupção não programada (:id)",
                'updated' => "Alteração de interrupção <b>não programada</b>",
                'canceled_subject' => "Cancelamento de interrupção não programada (:id)",
                'canceled' => "Cancelamento de interrupção <b>não programada</b>",
            ],
            'ref' => 'Ref.ª Interna',
            'disclaimer' => "Direitos reservados e da responsabilidade do grupo BEWG (PT).<br>Caso surja algum erro ou dúvida relacionado com esta mensagem contacte através do email <a href=':email'>:email</a>"
            // 'disclaimer' => "Os conteúdos desta mensagem são de carácter interno e confidencial, apenas dados relativos ao procedimento são apresentados para os respectivos responsáveis do mesmo, de forma meramente informativa e independente do fluxo do processo em causa. No caso de existir alguma informação errada queiram, por favor, informar os serviços de atendimento da <b>:company</b> através do número de apoio <b>:phone</b> ou pelo e-mail <b><a href='mailto://:email'>:email</a></b>. Poderão consultar as interrupções atualmente publicadas através da seguinte <a href=':website/interrupcoes'>ligação</a><br><br><u>Esta mensagem foi gerada automaticamente, não deve responder à mesma pois não será processada.</u>"
        ],
    ];
?>
