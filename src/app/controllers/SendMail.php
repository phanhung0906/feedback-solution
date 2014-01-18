<?php
class SendMail
{
        function perform() {
          $info = $this->args;
            if ($info['data']['send'] != $info['data']['receive']) {
                Mail::send('hungArray','emails.notify', $info['data'], function($message) use ($info)
                {
                    $message->from('htd530@gmail.com', 'pen.apl.vn');
                    $message->to($info['owner']['email'], $info['owner']['send'])->subject('Notify from pentool');
                });
            }
            //Send mail to guess
            $num = count($info['data']['email']);
            if ($num != 0) {
                $guess = array(
                    'email' => $info['data']['email'],
                    'send'  => $info['data']['send']
                );
                for ($i = 0; $i < $num ; $i++) {
                    Mail::send('hungArray','emails.guessMail', $info['data'], function($message) use ($guess,$i)
                    {
                        $message->from('htd530@gmail.com', 'pen.apl.vn');
                        $message->to($guess['email'][$i], $guess['send'])->subject('Notify from pentool');
                    });
                }
            }
    }
}