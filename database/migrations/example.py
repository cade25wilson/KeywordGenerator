import io
import os
import openai
from google.cloud import vision

# === CONFIG ===
GOOGLE_APPLICATION_CREDENTIALS = 'path/to/your-google-credentials.json'
openai.api_key = 'your-openai-api-key'
os.environ["GOOGLE_APPLICATION_CREDENTIALS"] = GOOGLE_APPLICATION_CREDENTIALS

# === LOAD IMAGE ===
def load_image_as_bytes(image_path):
    with io.open(image_path, 'rb') as image_file:
        return image_file.read()

# === STEP 1: GOOGLE VISION ANALYSIS ===
def extract_labels_and_text(image_bytes):
    client = vision.ImageAnnotatorClient()
    image = vision.Image(content=image_bytes)
    response = client.annotate_image({
        'image': image,
        'features': [
            {'type': vision.Feature.Type.LABEL_DETECTION},
            {'type': vision.Feature.Type.TEXT_DETECTION},
            {'type': vision.Feature.Type.LOGO_DETECTION},
        ]
    })

    labels = [label.description for label in response.label_annotations]
    logos = [logo.description for logo in response.logo_annotations]
    texts = [text.description for text in response.text_annotations]

    return labels, logos, texts

# === STEP 2: GPT KEYWORD GENERATION ===
def generate_keywords_with_gpt(labels, logos, texts):
    prompt = f"""
You are an expert e-commerce SEO assistant. Based on the following image data, generate a list of 15 popular, SEO-friendly keywords and product tags.

Labels: {', '.join(labels)}
Logos: {', '.join(logos)}
Text on item: {', '.join(texts)}

Respond with only a comma-separated list of keywords.
"""
    response = openai.ChatCompletion.create(
        model="gpt-4",
        messages=[{"role": "user", "content": prompt}],
        temperature=0.7,
    )

    return response.choices[0].message.content.strip()

# === MAIN FUNCTION ===
def process_image(image_path):
    print(f"Processing: {image_path}")
    image_bytes = load_image_as_bytes(image_path)
    labels, logos, texts = extract_labels_and_text(image_bytes)

    print("Detected labels:", labels)
    print("Detected logos:", logos)
    print("Detected text:", texts)

    keywords = generate_keywords_with_gpt(labels, logos, texts)
    print("\n✅ SEO Keywords:\n", keywords)

# === RUN EXAMPLE ===
if __name__ == '__main__':
    process_image('path/to/your/product_image.jpg')
