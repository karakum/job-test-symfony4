<?php

namespace App\Controller;

use App\Service\Text\TextProcessorRegistry;
use BadMethodCallException;
use Exception;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class TextController extends Controller
{
    /**
     * @Route("/", name="app_text_process")
     * @Method("POST")
     * @param Request $request
     * @return JsonResponse
     */
    public function listAction(Request $request)
    {
        $content = $request->getContent();
        $params = json_decode($content, true);
        if (empty($content) || empty($params)
            || !isset($params['job'])
            || !isset($params['job']['text'])
            || !isset($params['job']['methods'])
        ) {

            return new JsonResponse(['error' => 'Empty content'], Response::HTTP_BAD_REQUEST);
        }

        $text = $params['job']['text'];
        $errors = [];
        /** @var TextProcessorRegistry $processorService */
        $processorService = $this->get('app.text_processor_service');
        foreach ($params['job']['methods'] as $m) {
            try {
                $text = $processorService->process($m, $text);
            } catch (BadMethodCallException $e) {
                $errors[] = $e->getMessage();
            } catch (Exception $e) {

                return new JsonResponse(['error' => $e->getMessage()], 500);
            }
        }
        $data = [
            'text' => $text,
        ];
        if ($errors) {
            $data['errors'] = $errors;
        }

        return new JsonResponse($data, 200);
    }

}
