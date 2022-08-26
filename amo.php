<?php
require_once('config.php');
require_once('vendor/autoload.php');

use League\OAuth2\Client\Token\AccessToken;
use \AmoCRM\Client\AmoCRMApiClient;
use AmoCRM\Collections\ContactsCollection;
use AmoCRM\Collections\CustomFieldGroupsCollection;
use AmoCRM\Collections\CustomFieldsValuesCollection;
use AmoCRM\Collections\Leads\LeadsCollection;
use AmoCRM\Collections\LinksCollection;
use AmoCRM\Collections\NullTagsCollection;
use AmoCRM\Exceptions\AmoCRMApiException;
use AmoCRM\Filters\LeadsFilter;
use AmoCRM\Models\CompanyModel;
use AmoCRM\Models\ContactModel;
use AmoCRM\Models\CustomFieldsValues\BirthdayCustomFieldValuesModel;
use AmoCRM\Models\CustomFieldsValues\DateTimeCustomFieldValuesModel;
use AmoCRM\Models\CustomFieldsValues\TextCustomFieldValuesModel;
use AmoCRM\Models\CustomFieldsValues\ValueCollections\NullCustomFieldValueCollection;
use AmoCRM\Models\CustomFieldsValues\ValueCollections\TextCustomFieldValueCollection;
use AmoCRM\Models\CustomFieldsValues\ValueModels\TextCustomFieldValueModel;
use AmoCRM\Models\CustomFieldsValues\MultitextCustomFieldValuesModel;
use AmoCRM\Models\CustomFieldsValues\ValueCollections\MultitextCustomFieldValueCollection;
use AmoCRM\Models\CustomFieldsValues\ValueModels\MultitextCustomFieldValueModel;
use AmoCRM\Models\LeadModel;
use Carbon\Carbon;
use League\OAuth2\Client\Token\AccessTokenInterface;
use AmoCRM\Models\Unsorted\BaseUnsortedModel;
use AmoCRM\Models\Unsorted\FormsMetadata;
use AmoCRM\Models\Unsorted\FormUnsortedModel;
use AmoCRM\Models\Unsorted\SipMetadata;
use AmoCRM\Collections\Leads\Unsorted\FormsUnsortedCollection;
use AmoCRM\Collections\Leads\Unsorted\SipUnsortedCollection;
use AmoCRM\Models\Factories\UnsortedModelFactory;
use Ramsey\Uuid\Uuid;


class AMO
{

    public $apiClient;

    function __construct()
    {
        $user = $this->GetUserCFG();
        $this->apiClient = new \AmoCRM\Client\AmoCRMApiClient($user['client_id'], $user['client_secret'], AMO_CLIENT_REDIRECT);
        $token = $this->GetToken();

        $this->apiClient->setAccessToken($token)->setAccountBaseDomain($token->getValues()['baseDomain'])->onAccessTokenRefresh(
            function (AccessTokenInterface $accessToken, string $baseDomain) {
                file_put_contents('amo/token.json', json_encode([
                    'accessToken' => $accessToken->getToken(),
                    'refreshToken' => $accessToken->getRefreshToken(),
                    'expires' => $accessToken->getExpires(),
                    'baseDomain' => $this->apiClient->getAccountBaseDomain(),
                ]));
            }
        );
    }


    public function AddLead()
    {
        try {

            $leadsService = $this->apiClient->leads();


            $lead = new LeadModel();
            $lead->setName('Заявка ☻ Павел Хроленко')->setPrice(666);
            $leadsCollection = new LeadsCollection();
            $leadsCollection->add($lead);
            $leadsCollection = $leadsService->add($leadsCollection);

            $contact = new ContactModel();
            $contact->setName('Павел');


            $links = new LinksCollection();
            $links->add($lead);

            $this->apiClient->contacts()->link($contactModel, $links);
        } catch (AmoCRMApiException $e) {
            var_dump($e);
        }
    }



    public function AddUnsortedCall($email, $phone)
    {

        try {

            $sipUnsortedCollection = new SipUnsortedCollection();
            $sipUnsorted = UnsortedModelFactory::createForCategory(BaseUnsortedModel::CATEGORY_CODE_SIP);
            $sipMetadata = new SipMetadata();
            $sipMetadata
                // ->setServiceCode('my_best_telephony')
                // ->setLink('https://example.com/example.mp3')
                ->setDuration(135)
                ->setCalledAt(mktime(date('h'), date('i'), date('s'), 10, 04, 2019))
                ->setPhone('135')
                ->setFrom($phone)
                ->setUniq(Uuid::uuid4())
                ->setIsCallEventNeeded(true);

            $unsortedLead = new LeadModel();
            $unsortedLead->setName('Заявка ☻ Павел Хроленко')
                ->setPrice(0);

            $unsortedContactsCollection = new ContactsCollection();
            $unsortedContact = new ContactModel();
            $unsortedContact->setName('Хроленко Павел');
            $unsortedContactsCollection->add($unsortedContact);

            $sipUnsorted
                //  ->setSourceName('ss')
                //  ->setSourceUid(Uuid::uuid4())
                // ->setCreatedAt(time())
                // ->setMetadata($sipMetadata)
                // ->setLead($unsortedLead)
                // ->setPipelineId(3166396)
                ->setContacts($unsortedContactsCollection);

            $sipUnsortedCollection->add($sipUnsorted);


            $unsortedService = $this->apiClient->unsorted();

            $sipUnsortedCollection = $unsortedService->add($sipUnsortedCollection);
        } catch (AmoCRMApiException $e) {
            echo ($e->getDescription() . $e->getTitle());
        }
    }





    public function GetToken()
    {
        if (!file_exists('amo/token.json')) {
            die('Джони где токен?');
        }
        $token = json_decode(file_get_contents('amo/token.json'), true);
        return new AccessToken([
            'access_token' => $token['accessToken'],
            'refresh_token' => $token['refreshToken'],
            'expires' => $token['expires'],
            'baseDomain' => $token['baseDomain'],
        ]);
    }
    private function GetCode()
    {
        if (!file_exists('amo/token.json')) {
            die('Джони где код?');
        }
        return json_decode(file_get_contents('amo/code.json'), true);
    }
    private function GetUserCFG()
    {
        if (!file_exists('amo/token.json')) {
            die('Джони где конфиги юзера?');
        }
        return json_decode(file_get_contents('amo/user.json'), true);
    }
}
