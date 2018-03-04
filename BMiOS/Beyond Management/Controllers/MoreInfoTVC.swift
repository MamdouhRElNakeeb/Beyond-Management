//
//  MoreInfoTVC.swift
//  Beyond Management
//
//  Created by Mamdouh El Nakeeb on 2/26/18.
//  Copyright © 2018 Beyond Management. All rights reserved.
//

import UIKit

class MoreInfoTVC: UITableViewController, UITextViewDelegate {
    
    @IBOutlet weak var seekForTV: UITextView!
    
    var moreInfoDelegate: MoreInfoDelegate?
    
    
    @IBOutlet weak var indicator: UIActivityIndicatorView!
    
    override func viewDidLoad() {
        super.viewDidLoad()
        
        
        seekForTV.addBorder(view: seekForTV, stroke: UIColor.greyMidColor(), fill: UIColor.clear, radius: 10, width: 2)
        seekForTV.delegate = self
        
    }
    
    @IBAction func cancelOnClick(_ sender: Any) {
        dismiss(animated: true, completion: nil)
    }
    
    @IBAction func finishOnClick(_ sender: Any) {
        
        if seekForTV.text.isEmpty || seekForTV.text == "What are you seeking for?"{
            let alert = UIAlertController(title: "Error", message: "Please say what are you seeking for", preferredStyle: UIAlertControllerStyle.alert)
            alert.addAction(UIAlertAction(title: "Try again", style: UIAlertActionStyle.default, handler: nil))
            self.present(alert, animated: true, completion: nil)
            print("missing seeking for")
            return
        }
        
        moreInfoDelegate?.info(seekFor: seekForTV.text)
    }
    
    func textViewDidBeginEditing(_ textView: UITextView) {
        
        if textView == seekForTV{
            if textView.text == "What are you seeking for?"{
                textView.text = ""
                textView.textColor = UIColor.black
            }
        }
        
    }
    
    func textViewDidEndEditing(_ textView: UITextView) {
        
        if textView == seekForTV{
            if textView.text == ""{
                textView.text = "What are you seeking for?"
                textView.textColor = UIColor.greyMidColor()
            }
        }
    }
    
}


protocol MoreInfoDelegate: class {
    func info(seekFor: String)
}

