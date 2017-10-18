<?php
defined('_JEXEC') or die;

class PlgContentTelegrambot extends JPlugin
{
  public $link;
  
  public function onContentAfterSave($context, $article, $isNew)
  {
    $this->link = "http://qashqadaryogz.uz/site/";
    $token = 'TOKEN';
    $channel = '@channel';
    $this->sendTelegram($token, $channel, $article);
    
  }
  public function sendTelegram($token, $channel, $article)
  {
      $url = 'https://api.telegram.org/bot' . $token . '/sendPhoto';
      $text = $article->title;
      $images  = json_decode($article->get("images"));
      $image   = $images->image_intro;

      $inlinekeys[] = array(
                        array(
                          "text" => JText::_('Подробно'),
                          "url" => JRoute::_($this->link . "index.php?option=com_content&view=article&id=".$article->id)
                        )
                      );
      $inlinekeyboard = array("inline_keyboard" => $inlinekeys);
      $content = array(
                      "chat_id" => $channel,
                      "caption" => $text,
                      "photo" => $this->link . $image,
                      "reply_markup" => $inlinekeyboard
                      );
      $ch = curl_init();
      curl_setopt($ch, CURLOPT_URL, $url);
      curl_setopt($ch, CURLOPT_HEADER, false);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
      curl_setopt($ch, CURLOPT_POST, 1);
      curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json"));
      curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($content));
      curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
      $result = curl_exec($ch);
      curl_close($ch);
  }
}
