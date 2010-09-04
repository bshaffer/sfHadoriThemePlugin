<?php

// test the process of viewing and saving editable areas
require_once dirname(__FILE__).'/../bootstrap/functional.php';

$browser = new sfTestFunctional(new sfBrowser());
$browser->setTester('doctrine', 'sfTesterDoctrine');
$browser->setTester('admin', 'sfTesterAdmin');

Doctrine_Query::create()->from('Company')->delete()->execute();
$company = new Company();
$company->name = 'Company Name';
$company->save();

$browser->info('1 - Visit list page')
  ->info('  1.1 - Start by being logged out.')
  ->get('/company')

  ->with('request')->begin()
    ->isParameter('module', 'company')
    ->isParameter('action', 'index')
  ->end()

  ->with('response')->begin()
    // ->info('  1.2 - The title should be empty - we are not logged in, so no default text for the empty title field.')
    // ->checkElement('.test_title h1', '')
    // ->info('  1.3 - The title tag should not have the editable class, or any extra markup')
    // ->checkElement('.test_title h1[class="test_editable_class_name"]', false)
  ->end()
;
// 
// $context = $browser->getContext(true);
// $context->getUser()->setAuthenticated(true);
// $context->getUser()->addCredential('test_credential');
// $context->getUser()->shutdown();
// $context->getStorage()->shutdown();
// 
// $browser->info('2 - Goto a page, now logged in with the correct credential')
//   ->get('/blog')
// 
//   ->with('response')->begin()
//     ->isStatusCode(200)
//     ->info('  2.1 - The title has the configured placeholder text')
//     ->checkElement('.test_title h1', '[Test edit]')
//     ->info('  2.2 - Check for the markup for the editor - should be present now')
//     ->checkElement('.test_title h1.test_editable_class_name', true)
//     ->checkElement('.test_body div div:last', 'Lorem ipsum')
//   ->end()
// ;
// 
// $blog->title = 'test blog';
// $blog->save();
// 
// $form = new BlogForm($blog);
// $form->useFields(array('title'));
// $browser->info('3 - Display and submit a simple form')
//   ->get('/service/content/form?model=Blog&pk=2&fields[]=title')
// 
//   ->with('request')->begin()
//     ->isParameter('module', 'ioEditableContent')
//     ->isParameter('action', 'form')
//   ->end()
// 
//   ->with('response')->begin()
//     ->isStatusCode(200)
//     ->checkForm($form)
//     ->checkElement('input[type=hidden][name=model][value=Blog]')
//     ->checkElement('input[type=hidden][name=pk][value='.$blog->id.']')
//     ->checkElement('input[type=hidden][name=form][value="BlogForm"]')
//     ->checkElement('input[type=hidden][name=form_partial][value=ioEditableContent/formFields]')
//     ->checkElement('input[type=hidden][name=partial][value=]')
//     ->checkElement('input[type=hidden][name="fields[]"][value=title]')
//   ->end()
// 
//   ->info('  3.1 - Submit with errors')
//   ->click('save', array('blog' => array(
//     'fake_field' => 'val',
//   )))
// 
//   ->with('request')->begin()
//     ->isParameter('module', 'ioEditableContent')
//     ->isParameter('action', 'update')
//   ->end()
// 
//   ->with('response')->begin()
//     ->isStatusCode(200)
//   ->end()
// ;
// 
// $browser->info('  3.2 - check the json response');
// $response = $browser->getResponse()->getContent();
// $json = json_decode($response);
// $browser->test()->is($json->error, 'There were 1 errors when submitting the form.', 'The ->error key comes back correctly');
// $browser->test()->like($json->response, '/id\=\"blog_title\"/', 'The ->respones key contains the re-rendered form fields');
// $browser->test()->like($json->response, '/Unexpected extra form field named \"fake_field\"/', '->respones contains the global errors');
// 
// $browser
//   ->get('/service/content/form?model=Blog&pk=2&fields[]=title')
//   ->info('  3.3 - Submit a valid form')
//   ->click('save', array('blog' => array(
//     'title' => 'new title',
//   )))
// 
//   ->with('doctrine')->begin()
//     ->check('Blog', array('title' => 'new title'))
//   ->end()
// ;
// $browser->info('  3.4 - check the json response');
// $response = $browser->getResponse()->getContent();
// $json = json_decode($response);
// $browser->test()->is($json->error, '', '->error is blank because the form submitted successfully');
// $browser->test()->like($json->response, '/id\=\"blog_title\"/', 'The ->respones key contains the re-rendered form fields');
// 
// $browser->info('  3.5 - Goto the show page for this content area')
//   ->get('/service/content/show?model=Blog&pk=2&fields%5B%5D=title')
// 
//   ->with('request')->begin()
//     ->isParameter('module', 'ioEditableContent')
//     ->isParameter('action', 'show')
//   ->end()
// 
//   ->with('response')->begin()
//     ->isStatusCode(200)
//     ->matches('/new title/')
//   ->end()
// ;
// 
// 
// 
// $form = new BlogBodyForm($blog);
// $browser->info('4 - Display and submit a complex form')
//   ->get('/service/content/form?model=Blog&pk=2&form=BlogBodyForm&form_partial=test%2FbodyForm&partial=test%2Fbody')
// 
//   ->with('request')->begin()
//     ->isParameter('module', 'ioEditableContent')
//     ->isParameter('action', 'form')
//   ->end()
// 
//   ->with('response')->begin()
//     ->isStatusCode(200)
//     ->checkForm($form)
//     ->checkElement('input[type=hidden][name=model][value=Blog]')
//     ->checkElement('input[type=hidden][name=pk][value='.$blog->id.']')
//     ->checkElement('input[type=hidden][name=form][value="BlogBodyForm"]')
//     ->checkElement('input[type=hidden][name=form_partial][value=test/bodyForm]')
//     ->checkElement('input[type=hidden][name=partial][value=test/body]')
//   ->end()
// 
//   ->info('  4.1 - Submit a valid form')
//   ->click('save', array('blog' => array(
//     'body' => 'new body',
//   )))
// 
//   ->with('response')->begin()
//     ->isStatusCode('200')
//   ->end()
// 
//   ->with('doctrine')->begin()
//     ->check('Blog', array('body' => 'new body'))
//   ->end()
// ;
// $browser->info('  4.2 - check the json response');
// $response = $browser->getResponse()->getContent();
// $json = json_decode($response);
// $browser->test()->is($json->error, '', '->error is blank because the form submitted successfully');
// $browser->test()->like($json->response, '/id\=\"blog_body\"/', 'The ->respones key contains the re-rendered form fields');
// 
// $browser->info('  4.3 - Goto the show page for this content area')
//   ->get('/service/content/show?model=Blog&pk=2&form=BlogBodyForm&form_partial=test%2FbodyForm&partial=test%2Fbody')
// 
//   ->with('request')->begin()
//     ->isParameter('module', 'ioEditableContent')
//     ->isParameter('action', 'show')
//   ->end()
// 
//   ->with('response')->begin()
//     ->isStatusCode(200)
//     ->matches('/new body/')
//   ->end()
// ;
// 
// 
// Doctrine_Query::create()->from('Blog')->delete()->execute();
// $browser->info('5 - Fill out a form with a new object')
//   ->get('/service/content/form?model=Blog&pk=null&fields[]=title')
// 
//   ->with('response')->begin()
//     ->isStatusCode(200)
//   ->end()
// 
//   ->click('save', array('blog' => array(
//     'title' => 'new blog post',
//   )))
// 
//   ->with('doctrine')->begin()
//     ->check('Blog', array('title' => 'new blog post'))
//   ->end()
// ;
// 
// $blog = Doctrine_Query::create()->from('Blog')->fetchOne();
// $browser->info('  5.1 - check the json response for the pk key');
// $response = $browser->getResponse()->getContent();
// $json = json_decode($response);
// $browser->test()->is($json->pk, $blog->id, '->pk is the id of the new Blog entry');