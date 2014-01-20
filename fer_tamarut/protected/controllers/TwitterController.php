<?php

require_once('twitteroauth.php');
require_once('configg.php');
date_default_timezone_set('Europe/Zagreb');
session_start();

class TwitterController extends Controller
{
	public $status=0;
	
	public function actionIndex()
	{
	    if (empty($_SESSION['access_token']) || empty($_SESSION['access_token']['oauth_token']) || empty($_SESSION['access_token']['oauth_token_secret'])) {
            header('Location: ./connect');
        } else{
            $access_token = $_SESSION['access_token'];

            $connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $access_token['oauth_token'], $access_token['oauth_token_secret']);

            /* If method is set change API call made. Test is called by default. */
            $content = $connection->get('account/verify_credentials');
	        $this->render('index',array('status'=>0));
	    }
	}

	public function actionMessages()
	{
	   
        $pomoc=$_POST['broj_inbox'];
		if (!is_numeric($pomoc))
		    $pomoc=-1; 
		if($pomoc>=0&&$pomoc<15){
		     
		 
            
            if (empty($_SESSION['access_token']) || empty($_SESSION['access_token']['oauth_token']) || empty($_SESSION['access_token']['oauth_token_secret'])) {
                header('Location: ./connect');
            }
             else{

                /* Get user access tokens out of the session. */
                $access_token = $_SESSION['access_token'];

                /* Create a TwitterOauth object with consumer/user tokens. */
                $connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $access_token['oauth_token'], $access_token['oauth_token_secret']);

                /* If method is set change API call made. Test is called by default. */
                $content = $connection->get('account/verify_credentials');

         	    $korisnik_id=$access_token['user_id'];
	           
			    $korisnik_primio=array();
				$korisnik_poslao= array();
			    $imenaprijatelja =array();
			    $podaci=Twittermessages::model()->findAllByAttributes(array('user_id'=>"$korisnik_id"));
			    $friends=Twitterfriends::model()->findAllByAttributes(array('user_id'=>"$korisnik_id"));
	 
	            if(count($podaci)==0){  
				    $this->status=1;
					$this->actionRefresh();
		            //$this->render('messages',array('status'=>1));
				} else{
				    foreach ($friends as $a){
					    $imenaprijatelja[$a->id]=$a->prijatelj;
					}
				    $datumbaza=$a->datee;
					$danas=date('Y-m-j');
					if($datumbaza==$danas){
					    $zeljenidatum = strtotime ( "-$pomoc day" , strtotime ( $danas) ) ;
				        foreach ($podaci as $a){
						    $datumobjekta= strtotime($a->vrijeme);
				            if($datumobjekta>=$zeljenidatum){
							    if($a->type=="korisnikprimio"){
							        if(array_key_exists($a->prijatelj,$korisnik_primio))
								        $korisnik_primio[$a->prijatelj]=$korisnik_primio[$a->prijatelj]+1;
								    else
								        $korisnik_primio[$a->prijatelj]=1;
					            } else{
								    if(array_key_exists($a->prijatelj,$korisnik_poslao))
								        $korisnik_poslao[$a->prijatelj]=$korisnik_poslao[$a->prijatelj]+1;
								    else
								        $korisnik_poslao[$a->prijatelj]=1;
								}
							}
                        }
                        $a=array_keys($korisnik_primio);
				        foreach ($a as $b){
				            if((!array_key_exists($b,$korisnik_poslao)))
					            $korisnik_poslao[$b]=0;
				        }
				        $a=array_keys($korisnik_poslao);
				        foreach ($a as $b){
				            if((!array_key_exists($b,$korisnik_primio)))
					            $korisnik_primio[$b]=0;
				        }						
                        arsort($korisnik_primio);
			
                        $this->render('messages',array('status'=>0,'korisnik_primio'=>$korisnik_primio, 'korisnik_poslao'=>$korisnik_poslao,'imena'=>$imenaprijatelja));							
					} else{
					    $this->status=3;
						$this->actionRefresh();
					    //$this->render('messages',array('status'=>3));
				    }
				}
	        }
        } else {
            $this->render('messages',array('status'=>2));		
	    }
	}
	
	public function actionFriend_re(){
	    /* Load required lib files. */
       
        require_once('twitteroauth/twitteroauth.php');
        require_once('configg.php');
        $pomoc=$_POST['broj_friend'];
		if (!is_numeric($pomoc))
		    $pomoc=-1; 
		if($pomoc>=0&&$pomoc<91){     
		 
            /* If access tokens are not available redirect to connect page. */
            if (empty($_SESSION['access_token']) || empty($_SESSION['access_token']['oauth_token']) || empty($_SESSION['access_token']['oauth_token_secret'])) {
                header('Location: ./connect');
            }
             else{

                /* Get user access tokens out of the session. */
                $access_token = $_SESSION['access_token'];

                /* Create a TwitterOauth object with consumer/user tokens. */
                $connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $access_token['oauth_token'], $access_token['oauth_token_secret']);

                /* If method is set change API call made. Test is called by default. */
                $content = $connection->get('account/verify_credentials');

         	    $korisnik_id=$access_token['user_id'];
	           
			    $retweet_friend=array();
				$retweet_user=array();
			    $imenaprijatelja =array();
			    $podaci=Twitterretweet::model()->findAllByAttributes(array('user_id'=>"$korisnik_id"));
			    $friends=Twitterfriends::model()->findAllByAttributes(array('user_id'=>"$korisnik_id"));
				$podaci_user=Twitteruserret::model()->findAllByAttributes(array('user_id'=>"$korisnik_id"));
	 
	            if(count($podaci)==0){  
				    $this->status=1;
					$this->actionRefresh();
		            //$this->render('retweet',array('status'=>1));
				} else{
				    foreach ($friends as $a){
					    $imenaprijatelja[$a->id]=$a->prijatelj;
					}
				    $datumbaza=$a->datee;
					$danas=date('Y-m-j');
					if($datumbaza==$danas){
					    $zeljenidatum = strtotime ( "-$pomoc day" , strtotime ( $danas) ) ;
				        foreach ($podaci as $a){
						    $datumobjekta= strtotime($a->vrijeme);
				            if($datumobjekta>$zeljenidatum&&$a->vlasnik!=$korisnik_id){
							    if(array_key_exists($a->vlasnik,$retweet_friend))
								    $retweet_friend[$a->vlasnik]=$retweet_friend[$a->vlasnik]+1;
							    else
								    $retweet_friend[$a->vlasnik]=1;
					        }
                        }
						foreach ($podaci_user as $a){
						    $datumobjekta= strtotime($a->vrijeme);
				            if($datumobjekta>$zeljenidatum&&$a->prijatelj!=$korisnik_id){
							    $v=explode(",", $a->prijatelj);
								foreach($v as $u){
							        if($u!=$korisnik_id){
									    if(array_key_exists($u,$retweet_user))
								            $retweet_user[$u]=$retweet_user[$u]+1;
							            else
								            $retweet_user[$u]=1;
					                }
								}
					        }
                        }
						$a=array_keys($retweet_friend);
				        foreach ($a as $b){
				            if((!array_key_exists($b,$retweet_user)))
					            $retweet_user[$b]=0;
				        }
				        $a=array_keys($retweet_user);
				        foreach ($a as $b){
				            if((!array_key_exists($b,$retweet_friend)))
					            $retweet_friend[$b]=0;
				        }	
                        arsort($retweet_friend);
                        $this->render('retweet',array('status'=>0,'retweet_friend'=>$retweet_friend, 'retweet_user'=>$retweet_user,'imena'=>$imenaprijatelja));							
					} else{
					    $this->status=3;
						$this->actionRefresh();
					    //$this->render('retweet',array('status'=>3));
				    }
				}
	        }
        } else {
            $this->render('retweet',array('status'=>2));		
	    }
	
	
	}
	
	
	public function actionHome(){
	    
        $pomoc=$_POST['home_broj'];
		if (!is_numeric($pomoc))
		    $pomoc=-1; 
		$rijeci=$_POST['home_rijeci'];
		if($pomoc>=0&&$pomoc<91&&strlen($rijeci)>0){     
		 
            /* If access tokens are not available redirect to connect page. */
            if (empty($_SESSION['access_token']) || empty($_SESSION['access_token']['oauth_token']) || empty($_SESSION['access_token']['oauth_token_secret'])) {
                header('Location: ./connect');
            }
             else{
                /* Get user access tokens out of the session. */
                $access_token = $_SESSION['access_token'];

                /* Create a TwitterOauth object with consumer/user tokens. */
                $connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $access_token['oauth_token'], $access_token['oauth_token_secret']);

                /* If method is set change API call made. Test is called by default. */
                $content = $connection->get('account/verify_credentials');

         	    $korisnik_id=$access_token['user_id'];
	           
			    $tweet_sadrzaj=array();
			    $tweet_vlasnik=array();
				$imenaprijatelja =array();
				
			    $podaci=Twitterhome::model()->findAllByAttributes(array('user_id'=>"$korisnik_id"));
			    $friends=Twitterfriends::model()->findAllByAttributes(array('user_id'=>"$korisnik_id"));
	 
	            if(count($podaci)==0){  
				    $this->status=1;
					$this->actionRefresh();
		            //$this->render('home',array('status'=>1));
				} else{
				    foreach ($friends as $a){
					    $imenaprijatelja[$a->id]=$a->prijatelj;
					}
				    $datumbaza=$a->datee;
					$danas=date('Y-m-j');
					if($datumbaza==$danas){
					    $zeljenidatum = strtotime ( "-$pomoc day" , strtotime ( $danas) ) ;
						
						$f=explode(",", $rijeci);
				        $kljucne_rijeci = array ();
				        $i=0;
				        foreach($f as $a){
	                        $a=trim($a);			 
     				        if(strlen($a)>0){
					            $a= strtolower($a); 
				                $kljucne_rijeci[$i]=$a;
						        $i++;
					        }
				        }
						
				        foreach ($podaci as $a){
						    $datumobjekta= strtotime($a->vrijeme);
				            if($datumobjekta>$zeljenidatum&&$a->vlasnik != $korisnik_id){
							    $sadrzaj=$a->sadrzaj;
                                $sadrzaj= strtolower($sadrzaj);
							    foreach($kljucne_rijeci as $rijec){
                                    if(strpos($sadrzaj, $rijec) !== false){
						                $tweet_sadrzaj[$a->id]=$a->sadrzaj;
                                        $tweet_vlasnik[$a->id]=$a->vlasnik;										
									    break;
								    }
							    }
							    	
							}		
                        }	
                       
                        $this->render('home',array('status'=>0,'tweet_sadrzaj'=>$tweet_sadrzaj,'tweet_vlasnik'=>$tweet_vlasnik,'imena'=>$imenaprijatelja));							
					} else{
					    $this->status=3;
						$this->actionRefresh();
					    //$this->render('home',array('status'=>3));
				    }
				}
	        }
        } else {
            $this->render('home',array('status'=>2));		
	    }
	}
	
	public function actionInterests(){
	
        $pomoc=$_POST['interesi'];
		$rijeci=explode(",", $pomoc);
		$kljucne_rijeci = array ();
		$i=0;
		foreach($rijeci as $a){
	        $a=trim($a);			 
     		if(strlen($a)>0){
			    $a= strtolower($a); 
				$kljucne_rijeci[$i]=$a;
			    $i++;
			}
		}
     		 
            /* If access tokens are not available redirect to connect page. */
        if (empty($_SESSION['access_token']) || empty($_SESSION['access_token']['oauth_token']) || empty($_SESSION['access_token']['oauth_token_secret'])) {
            header('Location: ./connect');
        }   else{
            /* Get user access tokens out of the session. */
            $access_token = $_SESSION['access_token'];

            /* Create a TwitterOauth object with consumer/user tokens. */
            $connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $access_token['oauth_token'], $access_token['oauth_token_secret']);

            /* If method is set change API call made. Test is called by default. */
            $content = $connection->get('account/verify_credentials');

         	$korisnik_id=$access_token['user_id'];
			
			$imenaprijatelja =array();
			$opis= array();
			$podaci=Twitterinterests::model()->findAllByAttributes(array('user_id'=>"$korisnik_id"));
			$friends=Twitterfriends::model()->findAllByAttributes(array('user_id'=>"$korisnik_id"));
			
			if(count($podaci)==0){  
				$this->status=1;
				$this->actionRefresh();
		        //$this->render('interests',array('status'=>1));
			} else{
				foreach ($friends as $a){
					$imenaprijatelja[$a->id]=$a->prijatelj;
			    }
				$datumbaza=$a->datee;
			    $danas=date('Y-m-j');
				if($datumbaza==$danas){
				    foreach ($podaci as $a){
						foreach($kljucne_rijeci as $b){
							$ime= strtolower($a->description);
						    if(strpos($ime, $b) !== false){
							    $opis[$a->id_prijatelj]=$a->description;
								break;
						    }
					    }		    
                    }	
                       
                    $this->render('interests',array('status'=>0,'opis'=>$opis,'imena'=>$imenaprijatelja));							
			    } else{
					$this->status=3;
					$this->actionRefresh();
					//$this->render('interests',array('status'=>3));
				}
			}
	    }
	}
	
	public function actionRefresh()
	{
   
        if (empty($_SESSION['access_token']) || empty($_SESSION['access_token']['oauth_token']) || empty($_SESSION['access_token']['oauth_token_secret'])) {
            header('Location: ./connect');
        } else{
            $access_token = $_SESSION['access_token'];
            $connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $access_token['oauth_token'], $access_token['oauth_token_secret']);

            $content = $connection->get('account/verify_credentials');

   
	        $korisnik_id=$access_token['user_id'];
	        $pomoc=90;
	        $danasnjidatum = date('Y-m-j');
            $zeljenidatum = strtotime ( "-$pomoc day" , strtotime ( $danasnjidatum ) ) ;
	        $vrijeme_retweet = array();
	        $vlasnik_retweet = array();
	        $ime_prijatelja = array();
	        //echo $korisnik_id;
	        //korisnik_retweets 
	
        	$a=$connection->get('https://api.twitter.com/1.1/statuses/mentions_timeline.json?count=200');
	
	        $paging=1;
	
         	while($paging==1){
	            $zadnji_id=0;
		        if(count($a)>=200)
		            $paging=1;
		        else
		            $paging=0;
			
	            foreach ($a as $b){
	                $vrijeme=$b->created_at;
		            $c=strtotime($vrijeme);
		            if ($c>=$zeljenidatum){
		                $vrijeme_retweet[$b->id_str]=$b->created_at;
		                $vlasnik_retweet[$b->id_str]=$b->user->id_str;
				        $zadnji_id=$b->id_str;
			            if(!array_key_exists($b->user->id_str,$ime_prijatelja))
			                $ime_prijatelja[$b->user->id_str]=$b->user->name;
		            }
	            }
		        if($paging==1)
		            $a=$connection->get("https://api.twitter.com/1.1/statuses/mentions_timeline.json?count=200&max_id=$zadnji_id");
	        }
	        $criteria = new EMongoCriteria;
            $criteria->user_id = $korisnik_id;
            $result = Twitterretweet::model()->deleteAll($criteria);
            $a=array_keys($vrijeme_retweet);				
            foreach ($a as $b){
		        $bd = new Twitterretweet();
                $bd->user_id="$korisnik_id";
	            $bd->datee=date('Y-m-j');
		        $bd->vlasnik="$vlasnik_retweet[$b]";
	            $bd->vrijeme="$vrijeme_retweet[$b]";
		        $bd->save();
            }
	
	        //korisnik retweetao
	        $vrijeme_kor_ret = array();
	        $upucen_retweet = array();
	
	        $a=$connection->get('https://api.twitter.com/1.1/statuses/user_timeline.json?count=200');
	
	        $paging=1;
	
	        while($paging==1){
	            $zadnji_id=0;
		        if(count($a)>=200)
		            $paging=1;
		        else
		            $paging=0;
	
                foreach($a as $b){
	                $vrijeme=$b->created_at;
		            $c=strtotime($vrijeme);
		            if ($c>=$zeljenidatum){
		                $vrijeme_kor_ret[$b->id_str]=$b->created_at;
			            foreach ($b->entities->user_mentions as $c) {
			                $zadnji_id=$b->id_str;
			                if(array_key_exists($b->id_str,$upucen_retweet)){
				                $upucen_retweet[$b->id_str]=$upucen_retweet[$b->id_str].','.$c->id_str;
				            }else {
			                    $upucen_retweet[$b->id_str]= $c->id_str;
			                }
				            if(!array_key_exists($c->id_str,$ime_prijatelja))
			                    $ime_prijatelja[$c->id_str]=$c->name;
			            }
		            }
                }
	            if($paging==1)
		            $a=$connection->get("https://api.twitter.com/1.1/statuses/user_timeline.json?count=200&max_id=$zadnji_id");
	        }	
	
            $result = Twitteruserret::model()->deleteAll($criteria);
            $a=array_keys($vrijeme_kor_ret);				
            foreach ($a as $b){
	            if (array_key_exists($b,$upucen_retweet)){
		            $bd = new Twitteruserret();
                    $bd->user_id="$korisnik_id";
	                $bd->datee=date('Y-m-j');
		            $bd->prijatelj="$upucen_retweet[$b]";
	                $bd->vrijeme="$vrijeme_kor_ret[$b]";
		            $bd->save();
		        }
            }
	
	        //interesi 
	        $a=$connection->get("https://api.twitter.com/1.1/friends/ids.json?user_id=$korisnik_id");
	        $prijatelji = array ();
	        $i=0;
	        foreach ($a->ids as $b){
	            $prijatelji[$i]=$b;
		        $i++;
            }
	
	        foreach ($prijatelji as $id){
	            $a=$connection->get("https://api.twitter.com/1.1/users/show.json?user_id=$id");
		        if(!array_key_exists($a->id_str,$ime_prijatelja))
		            $ime_prijatelja[$a->id_str]=$a->name;
		        $bd = new Twitterinterests();
                $bd->user_id="$korisnik_id";
	            $bd->datee=date('Y-m-j');
		        $bd->id_prijatelj="$id";
		        if(strlen($a->description)>0)
	                $bd->description="$a->description";
		        else
		            $bd->description=0;
		        $bd->save(); 
	        }
	
	
	        //poruke
	        $vrijeme_poruke=array();
	        $vlasnik_poruke=array();
	        $korisnik_poslao=array();
	        $a=$connection->get("https://api.twitter.com/1.1/direct_messages.json?count=200");
 	        $pomoc=14;
	        $danasnjidatum = date('Y-m-j');
            $zeljenidatum = strtotime ( "-$pomoc day" , strtotime ( $danasnjidatum ) ) ;		
            $paging=1;
	
	        while($paging==1){
	            $zadnji_id=0;
		        if(count($a)>=200)
		            $paging=1;
		        else
		            $paging=0;
		
	            foreach($a as $b){
	                $vrijeme_objekta=$b->created_at;
		            $vrijeme_objekta=strtotime($vrijeme_objekta);
		            if($vrijeme_objekta>=$zeljenidatum){
		                $vrijeme_poruke[$b->id_str]=$b->created_at;
		                $vlasnik_poruke[$b->id_str]=$b->sender->id_str;
		                $zadnji_id=$b->id_str;
				        if(!array_key_exists($b->sender->id_str,$ime_prijatelja))
			                $ime_prijatelja[$b->sender->id_str]=$b->sender->name;  
			        } else {
			            $paging=0;
				        break;
			        }
	            }
		        if($paging==1)
		            $a=$connection->get("https://api.twitter.com/1.1/direct_messages.json?count=200&max_id=$zadnji_id");
	
	        }	
	
	
	        $a=array_keys($vrijeme_poruke);
	        $result = Twittermessages::model()->deleteAll($criteria);
	        foreach($a as $b){
                if(array_key_exists($b,$vlasnik_poruke)){	
		            $bd = new Twittermessages();
                    $bd->user_id="$korisnik_id";
	                $bd->datee=date('Y-m-j');
			        $bd->id_poruke="$b";
			        $bd->type='korisnikprimio';
		            $bd->prijatelj="$vlasnik_poruke[$b]";
	                $bd->vrijeme="$vrijeme_poruke[$b]";
		            $bd->save();		
                }
	        }  
	
	        $a=$connection->get("https://api.twitter.com/1.1/direct_messages/sent.json?count=200");
	        $paging=1;
	        $vrijeme_poruke=array();
	        while($paging==1){
	            $zadnji_id=0;
		        if(count($a)>=200)
		            $paging=1;
		        else
		            $paging=0;
	            foreach($a as $b){
	                $vrijeme_objekta=$b->created_at;
		            $vrijeme_objekta=strtotime($vrijeme_objekta);
		            if($vrijeme_objekta>=$zeljenidatum){
		                $vrijeme_poruke[$b->id_str]=$b->created_at;
		                $korisnik_poslao[$b->id_str]=$b->recipient->id_str;
		                $zadnji_id=$b->id_str;
		                if(!array_key_exists($b->recipient->id_str,$ime_prijatelja))
			                $ime_prijatelja[$b->recipient->id_str]=$b->recipient->name;  
			        } else {
			            $paging=0;
				         break;
			        }
	            }
		        if($paging==1)
		            $a=$connection->get("https://api.twitter.com/1.1/direct_messages/sent.json?count=200&max_id=$zadnji_id");
	        }	
	        $a=array_keys($vrijeme_poruke);
	
	        foreach($a as $b){
                if(array_key_exists($b,$korisnik_poslao)){	
		            $bd = new Twittermessages();
                    $bd->user_id="$korisnik_id";
	                $bd->datee=date('Y-m-j');
			        $bd->id_poruke="$b";
			        $bd->type='korisnikposlao';
		            $bd->prijatelj="$korisnik_poslao[$b]";
	                $bd->vrijeme="$vrijeme_poruke[$b]";
		            $bd->save();		
                }
	        }
	
	
	        //Home
	        $a=$connection->get("https://api.twitter.com/1.1/statuses/home_timeline.json?count=200");
	        $vrijeme_tweeta= array();
	        $vlasnik_tweeta =array();
	        $sadržaj_tweeta = array();
	        $pomoc=7;
	        $danasnjidatum = date('Y-m-j');
            $zeljenidatum = strtotime ( "-$pomoc day" , strtotime ( $danasnjidatum ) ) ;
	        $paging=1;
	        while($paging==1){
                $zadnji_id=0;	
                if(count($a)>=200)
		            $paging=1;
		        else
		            $paging=0;		
		        foreach($a as $b){
	                $vrijeme_objekta=$b->created_at;
		            $vrijeme_objekta=strtotime($vrijeme_objekta);
	                if($vrijeme_objekta>=$zeljenidatum){
		                $vrijeme_tweeta[$b->id_str]=$b->created_at;
		                $vlasnik_tweeta[$b->id_str]=$b->user->id_str;
		                $sadržaj_tweeta[$b->id_str]=$b->text;
			            $zadnji_id=$b->id_str;
			            if(!array_key_exists($b->user->id_str,$ime_prijatelja))
			                $ime_prijatelja[$b->user->id_str]=$b->user->name;
		            } else {
			            $paging=0;
				        break;
		            }
 	            }
		        if($paging==1)
		            $a=$connection->get("https://api.twitter.com/1.1/statuses/home_timeline.json?count=200&max_id=$zadnji_id");
	
	        }
	        $result = Twitterhome::model()->deleteAll($criteria);
	        $a=array_keys($vrijeme_tweeta);
	        foreach($a as $b){
	            $bd = new Twitterhome();
                $bd->user_id="$korisnik_id";
	            $bd->datee=date('Y-m-j');
		        $bd->id="$b";
		        $bd->vlasnik="$vlasnik_tweeta[$b]";
	            $bd->vrijeme="$vrijeme_tweeta[$b]";
		        $bd->sadrzaj="$sadržaj_tweeta[$b]";
		        $bd->save();
	        }
	
	        $result = Twitterfriends::model()->deleteAll($criteria);
	        $a=array_keys($ime_prijatelja);
	        foreach($a as $b){
	            $bd = new Twitterfriends();
                $bd->user_id="$korisnik_id";
	            $bd->datee=date('Y-m-j');
		        $bd->prijatelj="$ime_prijatelja[$b]";
	            $bd->id="$b";
		        $bd->save();
	        }
	    }
		$this->render('index',array('status'=>$this->status));
		$this->status=0;
	}


public function actionConnect(){
require_once('configg.php');

if (CONSUMER_KEY === '' || CONSUMER_SECRET === '' || CONSUMER_KEY === 'CONSUMER_KEY_HERE' || CONSUMER_SECRET === 'CONSUMER_SECRET_HERE') {
  echo 'You need a consumer key and secret to test the sample code. Get one from <a href="https://dev.twitter.com/apps">dev.twitter.com/apps</a>';
  exit;
}

/* Build an image link to start the redirect process. */
$content = '<a href="./redirect"><img src="./images/lighter.png" alt="Sign in with Twitter"/></a>';
 
/* Include HTML to display on the page. */
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
  <head>
    <title>fer_tamarut</title>
    <meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
    <style type="text/css">
      img {border-width: 0}
      * {font-family:'Lucida Grande', sans-serif;}
    </style>
  </head>
  <body>
    <div>
      

      
      <hr />
      <?php if (isset($menu)) { ?>
        <?php echo $menu; ?>
      <?php } ?>
    </div>
    <?php if (isset($status_text)) { ?>
      <?php echo '<h3>'.$status_text.'</h3>'; ?>
    <?php } ?>
    <p>
      <pre>
        <?php print_r($content); ?>
      </pre>
    </p>

  </body>
</html>
<?php
}

public function actionRedirect(){



/* Build TwitterOAuth object with client credentials. */
$connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET);
 
/* Get temporary credentials. */
$request_token = $connection->getRequestToken(OAUTH_CALLBACK);

/* Save temporary credentials to session. */
$_SESSION['oauth_token'] = $token = $request_token['oauth_token'];
$_SESSION['oauth_token_secret'] = $request_token['oauth_token_secret'];
 
/* If last connection failed don't display authorization link. */
switch ($connection->http_code) {
  case 200:
    /* Build authorize URL and redirect user to Twitter. */
    $url = $connection->getAuthorizeURL($token);
    header('Location: ' . $url); 
    break;
  default:
    /* Show notification if something went wrong. */
    echo 'Could not connect to Twitter. Refresh the page or try again later.';
}
}



public function actionCallback(){


/* If the oauth_token is old redirect to the connect page. */
if (isset($_REQUEST['oauth_token']) && $_SESSION['oauth_token'] !== $_REQUEST['oauth_token']) {
  $_SESSION['oauth_status'] = 'oldtoken';
  header('Location: /clearsessions.php');
}

/* Create TwitteroAuth object with app key/secret and token key/secret from default phase */
$connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $_SESSION['oauth_token'], $_SESSION['oauth_token_secret']);

/* Request access tokens from twitter */
$access_token = $connection->getAccessToken($_REQUEST['oauth_verifier']);

/* Save the access tokens. Normally these would be saved in a database for future use. */
$_SESSION['access_token'] = $access_token;

/* Remove no longer needed request tokens */
unset($_SESSION['oauth_token']);
unset($_SESSION['oauth_token_secret']);

/* If HTTP response is 200 continue otherwise send to connect page to retry */
if (200 == $connection->http_code) {
  /* The user has been verified and the access tokens can be saved for future use */
  $_SESSION['status'] = 'verified';
  header('Location: ./index');
} else {
  /* Save HTTP status for error dialog on connnect page.*/
  header('Location: /clearsessions.php');
}



}
	
public function actionClearsessions(){
 
/* Load and clear sessions */

session_destroy();
 
/* Redirect to page with the connect to Twitter option. */
header('Location: ./connect');


}

	// Uncomment the following methods and override them if needed
	/*
	public function filters()
	{
		// return the filter configuration for this controller, e.g.:
		return array(
			'inlineFilterName',
			array(
				'class'=>'path.to.FilterClass',
				'propertyName'=>'propertyValue',
			),
		);
	}

	public function actions()
	{
		// return external action classes, e.g.:
		return array(
			'action1'=>'path.to.ActionClass',
			'action2'=>array(
				'class'=>'path.to.AnotherActionClass',
				'propertyName'=>'propertyValue',
			),
		);
	}
	*/
	
		function recursion($data, $razmak, $kljuc) {
  foreach ($data as $a => $b) {
    if (is_array($b)) {
         $this->recursion($b, "&nbsp&nbsp" . $razmak, $kljuc . "['$a']");
    } else {
         echo "{$razmak} {$kljuc}['$a'] = $b<br>";
    }
   }
}
}