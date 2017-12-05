//
//  RegisterVC.swift
//  Beyond Management
//
//  Created by Mamdouh El Nakeeb on 12/5/17.
//  Copyright © 2017 Beyond Management. All rights reserved.
//

import UIKit

class RegisterVC: UIViewController {

    @IBOutlet weak var fbLoginBtn: UIButton!
    @IBOutlet weak var signupBtn: UIButton!
    
    override func viewDidLoad() {
        super.viewDidLoad()
        
        // Do any additional setup after loading the view.
        
        initView()
    }
    
    func initView(){
        
        signupBtn.layer.cornerRadius = signupBtn.frame.height / 2
        fbLoginBtn.layer.cornerRadius = fbLoginBtn.frame.height / 2
    }
    
    @IBAction func signupBtnClick(_ sender: UIButton) {
        
    }
    
    @IBAction func linkToLoginClick(_ sender: UIButton) {
        let loginVC = self.storyboard?.instantiateViewController(withIdentifier: "loginVC") as? LoginVC
        self.present(loginVC!, animated: true, completion: nil)
    }

}
