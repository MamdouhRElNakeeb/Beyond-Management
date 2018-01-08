//
//  RequirementsTVC.swift
//  Beyond Management
//
//  Created by Mamdouh El Nakeeb on 1/6/18.
//  Copyright © 2018 Beyond Management. All rights reserved.
//

import UIKit
import Alamofire
import AVFoundation
import Photos

class RequirementsTVC: UITableViewController {

    
    let imagePicker = UIImagePickerController()
    var urlTF = UITextField()
    var urlAlertView = UIAlertController()
    
    var uploadPV = UIProgressView()
    var uploadAV = UIAlertController()
    var timer = Timer()
    var progress: Float = 0
    
    let indicator = UIActivityIndicatorView(activityIndicatorStyle: UIActivityIndicatorViewStyle.white)
    
    var requirements = [Requirement]()
    
    var app = ImmigrationApplication()
    
    var reqID = ""
    
    override func viewDidLoad() {
        super.viewDidLoad()

        // Uncomment the following line to preserve selection between presentations
        // self.clearsSelectionOnViewWillAppear = false

        // Uncomment the following line to display an Edit button in the navigation bar for this view controller.
        // self.navigationItem.rightBarButtonItem = self.editButtonItem
        
        let barBtnItem = UIBarButtonItem(customView: indicator)
        self.navigationItem.rightBarButtonItem = barBtnItem
        
        self.imagePicker.delegate = self
        
        self.tableView.allowsSelection = false
        getRequirements()
    }

    func getRequirements (){
        
        let params: Parameters = [
            "app_id": app.id
        ]
        
        indicator.startAnimating()
        self.requirements.removeAll()
        
        Alamofire.request(Urls.APP_REQ, method: .post, parameters: params).responseJSON{
            
            response in
            
            print(response)
            
            if let result = response.result.value {
                
                let jsonArr = result as! NSArray
                
                for i in 0 ..< jsonArr.count {
                    let jsonObj = jsonArr.object(at: i) as! NSDictionary
                    self.requirements.append(Requirement.init(id: Int(jsonObj.value(forKey: "id") as! String)!,
                                                                    name: jsonObj.value(forKey: "name") as! String,
                                                                    info: jsonObj.value(forKey: "info") as! String,
                                                                    img: jsonObj.value(forKey: "img") as! String,
                                                                    status: jsonObj.value(forKey: "status") as! String))
                }
                
                self.indicator.stopAnimating()
                self.tableView.reloadData()
            }
        }
    }
    
    func submitDocUrl(url: String){
        
        let params: Parameters = [
            "req_id": reqID,
            "type": "1",
            "url": url
        ]
        
        Alamofire.request(Urls.REQ_SUBMIT, method: .post, parameters: params).responseJSON{
            
            response in
            
            if let result = response.result.value{
                
                if let json = result as? NSDictionary{
                    if !(json.value(forKey: "error") as! Bool){
                        let alert = UIAlertController(title: "Success", message: "Thank you, Your document have been submitted.\n You will receive a notification after reviewing it.", preferredStyle: UIAlertControllerStyle.alert)
                        alert.addAction(UIAlertAction(title: "OK", style: UIAlertActionStyle.default, handler: nil))
                        self.present(alert, animated: true, completion: nil)
                    }
                    else{
                        let alert = UIAlertController(title: "Error", message: json.value(forKey: "message") as? String, preferredStyle: UIAlertControllerStyle.alert)
                        alert.addAction(UIAlertAction(title: "Try Again", style: UIAlertActionStyle.destructive, handler: nil))
                        self.present(alert, animated: true, completion: nil)
                    }
                }
                
                self.getRequirements()
            }
            else{
                let alert = UIAlertController(title: "Error", message: "Upload document is faild!", preferredStyle: UIAlertControllerStyle.alert)
                alert.addAction(UIAlertAction(title: "Try Again", style: UIAlertActionStyle.destructive, handler: nil))
                self.present(alert, animated: true, completion: nil)
            }
        
        }
    }
    
    func uploadPhoto(fileData: Data){
        
        let parameters = [
            "req_id": reqID,
            "type": "0"
        ]
        
        print(parameters)
        
        indicator.startAnimating()
        
        Alamofire.upload(multipartFormData: { multipartFormData in
            multipartFormData.append(fileData, withName: "docFile", fileName: self.reqID + ".png", mimeType: "image/png")
            for (key, value) in parameters {
                multipartFormData.append(value.data(using: String.Encoding.utf8)!, withName: key)
            }
        },
                         to: Urls.REQ_SUBMIT)
        { (result) in
            switch result {
            case .success(let upload, _, _):
                
                self.uploadAV = UIAlertController(title: "Please wait", message: "Document is uploading", preferredStyle: .alert)
                
                //  Show it to your users
                self.present(self.uploadAV, animated: true, completion: {
                    
                    self.timer = Timer.scheduledTimer(timeInterval: 0.1, target: self, selector: #selector(self.syncProgress), userInfo: nil, repeats: true)
                    //  Add your progressbar after alert is shown (and measured)
                    let margin:CGFloat = 8.0
                    let rect = CGRect(x: margin, y: 72, width: self.uploadAV.view.frame.width - margin * 2.0 , height: 2.0)
                    self.uploadPV = UIProgressView(frame: rect)
                    self.uploadPV.progress = self.progress
                    
                    self.uploadPV.tintColor = UIColor.blue
                    self.uploadAV.view.addSubview(self.uploadPV)
                    
                })
                
                upload.uploadProgress(closure: { (progress) in
                    print("Upload Progress: \(progress.fractionCompleted)")
                    self.progress = Float(progress.fractionCompleted)
                })
                
                upload.responseJSON { response in
                    print("resVal: \(response.result.value)")
                    self.syncProgress()
                    
                    if let result = response.result.value{
                        
                        if let json = result as? NSDictionary{
                            if !(json.value(forKey: "error") as! Bool){
                                let alert = UIAlertController(title: "Success", message: "Thank you, Your document have been submitted.\n You will receive a notification after reviewing it.", preferredStyle: UIAlertControllerStyle.alert)
                                alert.addAction(UIAlertAction(title: "OK", style: UIAlertActionStyle.default, handler: nil))
                                self.present(alert, animated: true, completion: nil)
                            }
                            else{
                                let alert = UIAlertController(title: "Error", message: json.value(forKey: "message") as? String, preferredStyle: UIAlertControllerStyle.alert)
                                alert.addAction(UIAlertAction(title: "Try Again", style: UIAlertActionStyle.destructive, handler: nil))
                                self.present(alert, animated: true, completion: nil)
                            }
                        }
                        
                        self.getRequirements()
                    }
                    else{
                        let alert = UIAlertController(title: "Error", message: "Upload document is faild!", preferredStyle: UIAlertControllerStyle.alert)
                        alert.addAction(UIAlertAction(title: "Try Again", style: UIAlertActionStyle.destructive, handler: nil))
                        self.present(alert, animated: true, completion: nil)
                    }
                }
                
                self.indicator.stopAnimating()
                
            case .failure(let encodingError):
                print("uploadErr: \(encodingError)")
                
                self.syncProgress()
                self.indicator.stopAnimating()
                
                let alert = UIAlertController(title: "Error", message: "Upload document is faild!", preferredStyle: UIAlertControllerStyle.alert)
                alert.addAction(UIAlertAction(title: "Try Again", style: UIAlertActionStyle.destructive, handler: nil))
                self.present(alert, animated: true, completion: nil)
            }
        }
        
    }
    
    @objc func syncProgress() {
        
        self.uploadPV.progress = self.progress / 1.0
        print("progNow: \(self.uploadPV.progress)")
        if self.uploadPV.progress > 0.99 {
            timer.invalidate()
            uploadAV.dismiss(animated: true, completion: nil)
        }
    }
    
    @objc func chooseSubmissionMethod(_ sender: UIButton){
        
        reqID = "\(sender.tag)"
        
        //Create the AlertController
        let actionSheetController: UIAlertController = UIAlertController(title: nil, message: nil, preferredStyle: .actionSheet)
        
        //Create and add the Cancel action
        let cancelAction: UIAlertAction = UIAlertAction(title: "Cancel", style: .cancel) { action -> Void in
            //Just dismiss the action sheet
            
        }
        actionSheetController.addAction(cancelAction)
        
        //Create and add first option action
        let captureAction: UIAlertAction = UIAlertAction(title: "Camera", style: .default) { action -> Void in
            
            let status = AVCaptureDevice.authorizationStatus(for: AVMediaType.video)
            if (status == .authorized || status == .notDetermined) {
                if UIImagePickerController.isSourceTypeAvailable(.camera) {
                    self.imagePicker.sourceType = .camera
                    self.imagePicker.allowsEditing = false
                    self.present(self.imagePicker, animated: true, completion: nil)
                }
                else{
                    let alert = UIAlertController(title: "Error", message: "Camera not available!", preferredStyle: UIAlertControllerStyle.alert)
                    alert.addAction(UIAlertAction(title: "Try Again", style: UIAlertActionStyle.destructive, handler: nil))
                    self.present(alert, animated: true, completion: nil)
                }
            }
            
        }
        captureAction.setValue(UIImage(named: "camera_icn"), forKey: "image")
        actionSheetController.addAction(captureAction)
        
        let uploadFileAction: UIAlertAction = UIAlertAction(title: "Photo Library", style: .default) { action -> Void in
            
            let status = PHPhotoLibrary.authorizationStatus()
            if (status == .authorized || status == .notDetermined) {
                self.imagePicker.sourceType = .savedPhotosAlbum
                self.present(self.imagePicker, animated: true, completion: nil)
            }
            
        }
        uploadFileAction.setValue(UIImage(named: "image_icn"), forKey: "image")
        actionSheetController.addAction(uploadFileAction)
        
        let addUrlAction: UIAlertAction = UIAlertAction(title: "Document Url", style: .default) { action -> Void in
            
            self.urlAlertView = UIAlertController(title: "Add Document Url", message: "", preferredStyle: .alert)
            self.urlAlertView.addAction(UIAlertAction(title: "Cancel", style: .destructive, handler: nil))
            self.urlAlertView.addAction(UIAlertAction(title: "Submit", style: .default , handler: { (action) -> Void in
                // Get TextFields text
                let url = self.urlAlertView.textFields![0].text
                self.submitDocUrl(url: url!)
            }))
            
            self.urlAlertView.addTextField { (textField: UITextField) in
                textField.keyboardAppearance = .light
                textField.keyboardType = .default
                textField.autocorrectionType = .no
                textField.placeholder = "Document Url"
                textField.clearButtonMode = .whileEditing
            }
            
            self.present(self.urlAlertView, animated: true, completion: nil)
            
        }
        addUrlAction.setValue(UIImage(named: "doc_icn"), forKey: "image")
        actionSheetController.addAction(addUrlAction)
        
        //Present the AlertController
        self.present(actionSheetController, animated: true, completion: nil)
        
    }
    
    // MARK: - Table view data source

    override func numberOfSections(in tableView: UITableView) -> Int {
        // #warning Incomplete implementation, return the number of sections
        return 1
    }
    
    override func tableView(_ tableView: UITableView, heightForRowAt indexPath: IndexPath) -> CGFloat {
        return 150
    }
    
    override func tableView(_ tableView: UITableView, numberOfRowsInSection section: Int) -> Int {
        // #warning Incomplete implementation, return the number of rows
        return requirements.count
    }

    
    override func tableView(_ tableView: UITableView, cellForRowAt indexPath: IndexPath) -> UITableViewCell {
        
        let cell = tableView.dequeueReusableCell(withIdentifier: "RequirementTVCell", for: indexPath) as! RequirementTVCell

        // Configure the cell...
        cell.docName.text = requirements[indexPath.row].name
        cell.statusLbl.text = requirements[indexPath.row].status
        
        switch requirements[indexPath.row].status {
        case "new":
            cell.uploadBtn.isHidden = false
            cell.statusLbl.textColor = UIColor.red
            break
        case "waiting":
            cell.uploadBtn.isHidden = true
            cell.statusLbl.textColor = UIColor.yellow
            break
        case "rejected":
            cell.uploadBtn.isHidden = false
            cell.statusLbl.textColor = UIColor.red
            break
        case "approved":
            cell.uploadBtn.isHidden = true
            cell.statusLbl.textColor = UIColor.green
            break
        default:
            cell.uploadBtn.isHidden = true
            break
        }
        cell.uploadBtn.tag = requirements[indexPath.row].id
        cell.uploadBtn.addTarget(self, action: #selector(chooseSubmissionMethod(_:)), for: .touchUpInside)
        cell.docIV.sd_setImage(with: URL(string: Urls.DOC_IMGS + requirements[indexPath.row].img), placeholderImage: UIImage(named: "visa"))
        
        return cell
    }
    
}

extension RequirementsTVC: UIImagePickerControllerDelegate, UINavigationControllerDelegate{
    
    func imagePickerController(_ picker: UIImagePickerController, didFinishPickingMediaWithInfo info: [String : Any]) {
        if let pickedImage = info[UIImagePickerControllerEditedImage] as? UIImage {
            let photo = UIImagePNGRepresentation(pickedImage)
            self.uploadPhoto(fileData: photo!)
        } else {
            let pickedImage = info[UIImagePickerControllerOriginalImage] as! UIImage
            let photo = UIImagePNGRepresentation(pickedImage)
            self.uploadPhoto(fileData: photo!)
        }
        picker.dismiss(animated: true, completion: nil)
    }
}
