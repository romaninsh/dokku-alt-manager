<?php

/**
 * This page implements support for storing private and public keys
 */
class page_keychain extends Page {

    public $title = 'Keychain';
    public $cr_reload;

    function init() {
        parent::init();

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
}
