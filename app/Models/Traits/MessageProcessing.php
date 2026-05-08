<?php

namespace App\Models\Traits;

use FastVolt\Helper\Markdown;
use Throwable;

trait MessageProcessing
{
  private array  $roles  = [
    'assistant' => 'ChatGPT',
    'user'      => 'User'
  ];

  /**
   * Process the passed row from the SQL query and organize it as an array.
   * 
   * @param array $message The row content.
   * @return ?array An array with the message ID, the conversation ID, the
   *                author and their role, the creation date, and the formatted
   *                message body.
   */
  private function processMessage(array $message): ?array
  {
    if (isset($message['contentParts'][0])) {
      $contentParts = json_decode($message['contentParts']);
      if (is_array($contentParts) && isset($contentParts[0])) {
        try {
          // Consider only messages from the user and ChatGPT
          $currRole = $message['authorRole'] ?? null;
          if (isset($this->roles[$currRole])) {
            $msgCreated = timestampToFormattedDate($message['createdTime']);

            // Message meta: author, created and updated
            $msgMeta = "{$this->roles[$currRole]} @ $msgCreated";

            // Message body: Markdown to HTML
            $msgPart = (string) $contentParts[0] ?? '';
            if ($this->roles[$currRole] === 'ChatGPT') {
              $markdown = new Markdown();
              $markdown->setContent($msgPart);
              $msgBody = $markdown->toHtml();
              unset($markdown);
            } else {
              $msgBody = nl2br($msgPart);
            }
            
            // Clear the cites in messages from web searches
            $citeRegex = '/\x{e200}cite(\x{e202}turn\d+search\d+)+\x{e201}/u';
            $msgBody = preg_replace($citeRegex, '', $msgBody);

            return [
              'id'     => $message['messageId'] ?? null,
              'cid'    => $message['conversationId'] ?? null,
              'meta'   => $msgMeta,
              'body'   => $msgBody,
              'author' => $currRole
            ];
          }
        } catch (Throwable $e) {
          logErrorWithTimestamp($e, __FILE__);
          return null;
        }
      }
    }
    return null;
  }

  /**
   * Create a block for the retrieved message content.
   * 
   * @param array $results        The retrieved rows from the SQL database.
   * @param bool  $includeGoToBtn If a link to the full conversation should be
   *                              included. True for search results, otherwise
   *                              false.
   * @return string A div block with the formatted message.
   */
  public function createMessageBlock(array $results, bool $includeGoToBtn = false): string
  {
    $msgMeta = $msgBody = null;
    $userClass = $messageBlock = '';
    $raw = $this->processMessage($results);

    if ($raw !== null) {
      $msgID     = $raw['id'];
      $msgChatID = $raw['cid'] ?? null;
      $msgMeta   = $raw['meta'] ?? null;
      $msgBody   = $raw['body'] ?? null;
      $chatLink  = url('chat') . '?id=' . $msgChatID;
      $userClass = ($raw['author'] === 'assistant') ? ' chat-response' : '';
      $btnGoTo   = $includeGoToBtn ? "<div class=\"go-to-btn\" title=\"View full chat\"><a class=\"chat-link\" href=\"{$chatLink}\">View full chat >></a></div>" : '';
      $messageBlock .= <<<CURR_MESSAGE
      <div id="{$msgID}" class="msg-body{$userClass}">
        <p>
        <small>
        {$msgMeta}
        </small>
        </p>
        <div>{$msgBody}</div>{$btnGoTo}
      </div>
      CURR_MESSAGE;
    }

    return $messageBlock;
  }
}
