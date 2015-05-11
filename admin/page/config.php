<?php
/**
 * Page class
 */
class page_config extends Page
{
    public $title='Configuration';

    function page_index()
    {
        $tt = $this->add('Tabs');
        $general_tab = $tt->addTab('General');

        $f=$general_tab->add('Form');
        $m = $f->setModel('Config', ['is_github_push_enabled','is_restapi_enabled'])->tryLoad(1);
        $f->addSubmit();
        $f->onSubmit(function($f){
            $f->save();
            return $f->js()->redirect($this->app->url());
        });


        $tt->addTabURL('./packing','Packing Key');
        $tt->addTabURL('./users','Web Users');
        if($m['is_github_push_enabled'])$tt->addTabURL('./github','GitHub Push');
        if($m['is_restapi_enabled'])$tt->addTabURL('./restapi','RestAPI');


    }

    function page_users(){

        $user=$this->add('Model_User');
        $this->app->auth->addEncryptionHook($user);

        if((string)$user->count() < 1) {
            $user->addHook('afterSave', function($m){
                // finalize and go logout...
                @$this->app->auth->logout(false);
                $m->_dsql()->owner->commit();
                $m->app->js()->univ()->location($this->app->url('index'))->execute();
            });
        }

        $user = $this->add('CRUD')->setModel($user);
    }

    function page_packing()
    {

        // $this->add('H2')->set('Packing Key');
        // $this->add('View_Hint')->set('Packing key is a passphrase to encrypt all of your public keys. Clicking [Verify] will ask you to enter a key then attempt to unlock the key.');

        // $grid = $this->add('Grid');
        // $grid->setModel('Keychain');
        // $grid->addColumn('button','verify');

        // $grid->addButton('Enable for all keys');
        // $grid->addButton('Change your packing key');
        // $grid->addButton('Disable packing key');
        $cr = $this->add('CRUD');
        $cr ->setModel('Keychain');

        $cr->addAction('verify','column');

        $this->cr_reload = $cr->js()->reload();

        $pakbut = $cr->addButton(($this->api->getPackingKey() ? 'Update' : 'Set') . ' Packing Key')
            ->setIcon('lock-open-alt');
        $pak_rl = $pakbut->js()->reload();

        $pakbut
            ->on('click', $this->add('View_Popover')
            ->set(function($p) use ($pak_rl){
                $f = $p->add('Form');
                $fld = $f->addField('password', 'packing_key');
                $fld->afterField()
                    ->add('Button')
                    ->set('Save')
                    ->on('click', $f->js()->submit())
                ;

                $f->onSubmit(function($f) use ($pak_rl){
                    $this->api->memorize('packing_key', $f['packing_key']);
                    $f->js(null, $pak_rl)
                        ->html('<h4>Key Temporarily Stored</h4>')
                        ->univ()->closeDialog()
                        ->execute()
                    ;
                });
            })
            ->showJS());

        $cr->addButton('Add Access Key')->setIcon('key')
            ->on(
                'click',
                $this->add('View_Popover')
                ->set(array($this, 'setAccessKey'))
                ->showJS(array('width' => 600))
            );
    }

    function setAccessKey($p) {
        $pack = $p->api->getPackingKey();

        if (!$pack) {
            $p->add('View_Warning')->set('You must set packing key to generate secure item');
        }

        $p->add('H3')
            ->set('Adding a new Access Key');

        $p->add('View_Info')
            ->set('Access key will permit you to logit to servers through SSH and SFTP. ExDash will create
            a secure key for you and encrypt it with your packing key, so that only you could use it. You
            may store private part of your key in your local application if you wish to access servers
            directly.');

        $f = $p->add('Form');
        $f->addField('Checkbox', 'is_secure')
            ->setFieldHint('Additionally will encrypt your private key with your packing key')
            ->set(true);
        $f->addSubmit('Generate');

        $f->onSubmit(function($f) use($pack){
            try {
                if (!$pack && $f['is_secure'])
                    $f->displayError('is_secure','Packing key is not set');

                $m = $this->add('Model_Keychain');
                $m->generateKey($pack);
                $m->save();

                $f->js(null, $this->cr_reload)->html('Key added and stored')->univ()->closeDialog()->execute();
            }catch(Exception $e){
                if($e instanceof BaseException) return $f->js()->univ()->alert($e->getText());
                return $f->js()->univ()->alert($e->getMessage());
            }
        });
    }

    function page_github()
    {

        $this->add('H2')->set('GitHub Push');
        $this->add('View_Hint')->set('Use the following end-point for pushes: http://yahoo.com/');

        $this->add('H3')->set('Recent Push Log');
        $this->add('Grid')->setModel('GitHubPushLog');


    }

    function page_restapi()
    {
        $this->add('H2')->set('Rest API');
        $this->add('View_Hint')->set('Your end-point for rest API: http://yahoo.com/');

        $f = $this->add('Form');

        $f->setModel('Config', ['rest_api_shared_secret'])->tryLoad(1);
        //$fld = $f->addField('rest_api_shared_secret');
        $fld = $f->getElement('rest_api_shared_secret');
        $fld_but = $fld->addButton(['Randomize', 'icon'=>'shuffle']);
        $fld_but->onClick(function($button)use($fld){
            $pw = new PWGen();
            return $fld->js()->val($pw->generate());
        });

        $f->addSubmit();
        $f->onSubmit(function($f){
            $f->update();
            return 'Saved';
        });
    }
}
