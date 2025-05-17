import sys
import json
import joblib
import numpy as np

# Load the pre-trained model
model_path = "heart_model.sav"
model = joblib.load(model_path)

def predict_heart_disease(inputs):
    # Reshape inputs to match model's expected input shape
    input_array = np.array(inputs).reshape(1, -1)
    # Get prediction (0 for no heart disease, 1 for heart disease)
    prediction = model.predict(input_array)
    # Return the result
    return int(prediction[0])

if __name__ == "__main__":
    # Expect 14 inputs in the order specified in the PHP file
    if len(sys.argv) != 14:
        print(json.dumps({"error": "Invalid input. Expected 13 input values."}))
        sys.exit(1)
    
    try:
        # Parse inputs as floats
        inputs = [float(arg) for arg in sys.argv[1:]]
        # Get prediction result
        result = predict_heart_disease(inputs)
        # Output the result as JSON
        print(json.dumps({"result": result}))
    
    except ValueError:
        print(json.dumps({"error": "Invalid input. All values must be numeric."}))
        sys.exit(1)
