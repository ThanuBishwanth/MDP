import sys
import json
import joblib
import numpy as np

# Load the pre-trained model
model_path = "liver_model.sav"
try:
    model = joblib.load(model_path)
except Exception as e:
    print(json.dumps({"error": f"Error loading model: {str(e)}"}))
    sys.exit(1)

def predict_liver_disease(inputs):
    # Reshape inputs to match model's expected input shape
    input_array = np.array(inputs).reshape(1, -1)
    # Get prediction (0 for no liver disease, 1 for liver disease)
    try:
        prediction = model.predict(input_array)
        return int(prediction[0])
    except Exception as e:
        return {"error": f"Prediction error: {str(e)}"}

if __name__ == "__main__":
    # Expect 10 inputs in the order specified in the PHP file
    if len(sys.argv) != 11:
        print(json.dumps({"error": "Invalid input. Expected 10 input values."}))
        sys.exit(1)
    
    try:
        # Parse inputs as floats
        inputs = [float(arg) for arg in sys.argv[1:]]
        # Get prediction result
        result = predict_liver_disease(inputs)
        # Output the result as JSON
        print(json.dumps({"result": result}))
    
    except ValueError:
        print(json.dumps({"error": "Invalid input. All values must be numeric."}))
        sys.exit(1)
